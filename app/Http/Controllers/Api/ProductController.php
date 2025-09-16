<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PriceSortRequest;
use App\Http\Requests\Api\FilterSortRequest;
use App\Http\Resources\Api\Product\PriceSortResource;
use App\Repositories\Interfaces\Api\Product\ProductsInterface;

class ProductController extends Controller
{
    protected $productsRepository;

    public function __construct(ProductsInterface $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function priceSort(PriceSortRequest $request){

       try {
          $allProducts = $this->productsRepository->priceSort($request);

         return response()->json([
                'response' => [
                    'status' => true,
                    'data' => [
                        'products' => PriceSortResource::collection($allProducts),
                    ],
                ],
            ], JsonResponse::HTTP_OK);
       } catch (\Exception $e) {
         return response()->json([
                'response' => [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
       }
    }

    public function filter(FilterSortRequest $request){

       try {
          $allProducts = $this->productsRepository->filter($request);

         return response()->json([
                'response' => [
                    'status' => true,
                    'data' => [
                        'products' => PriceSortResource::collection($allProducts),
                    ],
                ],
            ], JsonResponse::HTTP_OK);
       } catch (\Exception $e) {
         return response()->json([
                'response' => [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
       }
    }

    public function search(Request $request){

       try {
          $allProducts = $this->productsRepository->search($request);

         return response()->json([
                'response' => [
                    'status' => true,
                    'data' => [
                        'products' => PriceSortResource::collection($allProducts),
                    ],
                ],
            ], JsonResponse::HTTP_OK);
       } catch (\Exception $e) {
         return response()->json([
                'response' => [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
       }
    }
}
