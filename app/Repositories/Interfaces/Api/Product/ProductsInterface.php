<?php

namespace App\Repositories\Interfaces\Api\Product;

interface ProductsInterface
{
    /**
     * priceSort Products
     *
     * @param $request
     * @return mixed
     */
    public function priceSort($request);

    /**
     * filter Products
     *
     * @param $request
     * @return mixed
     */
    public function filter($request);

    /**
     * search Products
     *
     * @param $request
     * @return mixed
     */
    public function search($request);
}
