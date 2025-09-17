<?php
namespace App\Repositories\Repository\Product;

use App\Models\Product;
use App\Repositories\Interfaces\Product\ProductInterface;
use Exception;
use Illuminate\Support\Facades\DB;

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

            $query = Product::query()
                ->leftJoinSub(
                    DB::table('reviews')
                        ->selectRaw('productId, AVG(rating) as reviews_avg_rating')
                        ->groupBy('productId'),
                    'reviews_avg',
                    'products.id',
                    '=',
                    'reviews_avg.productId'
                );

            if ($search !== '') {
                $searchLike = "%{$search}%";

                $query->where(function ($q) use ($searchLike) {
                    $q->where('title', 'like', $searchLike)
                        ->orWhere('description', 'like', $searchLike)
                        ->orWhere('tag', 'like', $searchLike);
                });

                // Count keyword occurrences
                $relevanceSql = "
        (
            (LENGTH(title) - LENGTH(REPLACE(LOWER(title), LOWER(?), ''))) / LENGTH(?)
          + (LENGTH(description) - LENGTH(REPLACE(LOWER(description), LOWER(?), ''))) / LENGTH(?)
          + (LENGTH(tag) - LENGTH(REPLACE(LOWER(tag), LOWER(?), ''))) / LENGTH(?)
        )
    ";

                $query->select('products.*') // keep reviews_avg_rating intact
                    ->addSelect('reviews_avg_rating')
                    ->selectRaw("$relevanceSql as relevance_score", [
                        $search, $search,
                        $search, $search,
                        $search, $search,
                    ])
                    ->orderByRaw("
              CASE
                  WHEN isTopSeller = 1
                       AND relevance_score BETWEEN 5 AND 10
                       AND reviews_avg_rating > 0
                  THEN 0
                  WHEN relevance_score > 10
                  THEN 2
                  ELSE 1
              END
          ")
                    ->orderByDesc('reviews_avg_rating') 
                    ->orderByDesc('relevance_score');
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
