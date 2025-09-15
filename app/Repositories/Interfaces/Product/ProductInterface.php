<?php

namespace App\Repositories\Interfaces\Product;

interface ProductInterface
{
    /**
     * Show Products
     *
     * @param $request
     * @return mixed
     */
    public function showProducts($request);
}
