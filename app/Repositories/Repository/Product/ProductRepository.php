<?php
namespace App\Repositories\Repository\Product;

use App\Models\Product;
use App\Repositories\Interfaces\Product\ProductInterface;
use Exception;

class ProductRepository implements ProductInterface
{
    /**
     * Show Products
     *
     * @param $request
     * @return mixed
     * @throws \Exception
     */

    public function showProducts($request)
    {
        try {
            $sortInput = $request->input('sort', []);
            $sorts     = is_array($sortInput) ? $sortInput : ($sortInput ? [$sortInput] : []);
            $sorts     = array_values(array_filter($sorts, fn($v) => $v !== null && $v !== ''));

            $search = trim((string) $request->input('search', ''));

            $query = Product::query();

            if ($search !== '') {
                $searchExact = $search;
                $searchLike  = "%{$search}%";

                // only products that match one of the fields
                $query->where(function ($q) use ($searchLike) {
                    $q->where('title', 'like', $searchLike)
                        ->orWhere('description', 'like', $searchLike)
                        ->orWhere('tag', 'like', $searchLike);
                });

                // 1) Primary ordering — exact title first, then title-contains, then description, then tag
                $exactOrderSql = "
                CASE
                    WHEN title = ? THEN 0
                    WHEN title LIKE ? THEN 1
                    WHEN description = ? THEN 2
                    WHEN description LIKE ? THEN 3
                    WHEN tag = ? THEN 4
                    WHEN tag LIKE ? THEN 5
                    ELSE 6
                END
            ";
                $query->orderByRaw($exactOrderSql, [
                    $searchExact, $searchLike,
                    $searchExact, $searchLike,
                    $searchExact, $searchLike,
                ]);

                $longTopPrioritySql = "
                CASE
                    WHEN (
                        (title LIKE ? AND (LENGTH(title) - LENGTH(REPLACE(title, ' ', '')) + 1) > 5)
                        OR (description LIKE ? AND (LENGTH(description) - LENGTH(REPLACE(description, ' ', '')) + 1) > 5)
                        OR (tag LIKE ? AND (LENGTH(tag) - LENGTH(REPLACE(tag, ' ', '')) + 1) > 5)
                    ) AND isTopSeller = 1 THEN 2
                    WHEN isTopSeller = 1 THEN 0
                    ELSE 1
                END
            ";
                $query->orderByRaw($longTopPrioritySql, [$searchLike, $searchLike, $searchLike]);

                $query->orderByRaw("CASE WHEN title LIKE ? THEN 0 ELSE 1 END", [$searchLike]);
            }

            // Apply filter conditions (these are combinable)
            if (in_array('topSell', $sorts)) {
                $query->where('isTopSeller', true);
            }

            if (in_array('discounted', $sorts)) {
                $query->where('discountPrice', '>', 0);
            }

            if (in_array('orignalPrice', $sorts)) {
                $query->whereNull('discountPrice');
            }

            // Price ordering (applied after relevance rules)
            if (in_array('lowToHigh', $sorts)) {
                $query->orderBy('originalPrice', 'asc');
            } elseif (in_array('highToLow', $sorts)) {
                $query->orderBy('originalPrice', 'desc');
            }

            // Default ordering when no search & no sorts
            if (empty($sorts) && $search === '') {
                $query->orderByDesc('isTopSeller')
                    ->orderByRaw('(discountPrice > 0) DESC')
                    ->orderBy('originalPrice', 'asc');
            }

            return $query->paginate(6);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
