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
            // Normalize sorts into an array
            $sortInput = $request->input('sort', []);
            $sorts     = is_array($sortInput) ? $sortInput : ($sortInput ? [$sortInput] : []);

            // remove empty values
            $sorts = array_values(array_filter($sorts, function ($value) {
                return $value !== null && $value !== '';
            }));

            $search = $request->input('search');

            $query = Product::query();

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('tag', 'like', "%{$search}%");
                });

                // Tag-length priority (DB-level) — keep this before price ordering
                $searchLength = strlen($search);
                if ($searchLength >= 5 && $searchLength <= 10) {
                    $query->orderByRaw("CASE WHEN LENGTH(tag) BETWEEN 5 AND 10 THEN 0 ELSE 1 END");
                } elseif ($searchLength > 10) {
                    $query->orderByRaw("CASE WHEN LENGTH(tag) > 10 THEN 1 ELSE 0 END");
                }
            }

            // Apply filter conditions (these are combinable)
            if (in_array('topSell', $sorts)) {
                $query->where('isTopSeller', true);
            }

            if (in_array('discounted', $sorts)) {
                $query->where('discountPrice', '>', 0);
            }

            if (in_array('orignalPrice', $sorts)) {
                // "original price" filter: products without discount
                $query->whereNull('discountPrice');
            }

            // Apply price ordering (only one should be present)
            if (in_array('lowToHigh', $sorts)) {
                $query->orderBy('originalPrice', 'asc');
            } elseif (in_array('highToLow', $sorts)) {
                $query->orderBy('originalPrice', 'desc');
            }

            // Default ordering if nothing applied (or as fallback)
            if (empty($sorts)) {
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
