<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\Product\ProductInterface;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function showProduct(Request $request)
    {
        $allProductsPaginated = $this->productRepository->showProducts($request);

        if ($request->ajax()) {
            return response()->json([
                'products'   => view('product.partials.products', compact('allProductsPaginated'))->render(),
                'pagination' => $allProductsPaginated->appends($request->query())
                    ->links('pagination.customPagination')->render(),
            ]);
        }

        return view('product.showProduct', compact('allProductsPaginated'));
    }

}
