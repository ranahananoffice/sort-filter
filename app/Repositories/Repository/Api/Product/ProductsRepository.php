<?php
namespace App\Repositories\Repository\Api\Product;

use Exception;
use App\Models\Product;
use App\Enums\PriceSortEnum;
use App\Enums\FilterSortEnum;
use App\Repositories\Interfaces\Api\Product\ProductsInterface;

class ProductsRepository implements ProductsInterface
{
    /**
     * priceSort Products
     *
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public function priceSort($request)
    {
        try {
            $query = Product::query();

            $query->orderByDesc('isTopSeller')
                ->orderByRaw('(discountPrice > 0) DESC');

            // Apply user sorting
            if ($request->sortOrder === PriceSortEnum::lowToHigh) {
                $query->orderBy('originalPrice', 'asc');
            } elseif ($request->sortOrder === PriceSortEnum::highToLow) {
                $query->orderBy('originalPrice', 'desc');
            } elseif (empty($request->sortOrder)) {
                $query->orderBy('originalPrice', 'asc');
            }

            $products = $query->get();

            return $products;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * filter Products
     *
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public function filter($request)
    {
        try {
            $query = Product::query();

            $query->orderByDesc('isTopSeller')
                ->orderByRaw('(discountPrice > 0) DESC');

            // Apply user sorting
            if ($request->sortOrder === FilterSortEnum::orignalPrice) {
                $query->whereNull('discountPrice');
            } elseif ($request->sortOrder === FilterSortEnum::discounted) {
                $query->where('discountPrice', '>', 0);
            } elseif ($request->sortOrder === FilterSortEnum::topSell) {
                $query->where('isTopSeller', true);
            }
            elseif (empty($request->sortOrder)) {
                $query->orderBy('originalPrice', 'asc');
            }

            $products = $query->get();

            return $products;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * search Products
     *
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public function search($request)
    {
        try {
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

            $products = $query->get();

            return $products;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
