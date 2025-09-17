<?php
namespace App\Repositories\Repository\Api\Product;

use App\Enums\FilterSortEnum;
use App\Enums\PriceSortEnum;
use App\Models\Product;
use App\Repositories\Interfaces\Api\Product\ProductsInterface;
use Exception;
use Illuminate\Support\Facades\DB;

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

            $products = $query->get();

            return $products;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
