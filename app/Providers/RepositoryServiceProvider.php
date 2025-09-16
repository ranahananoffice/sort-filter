<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\Product\ProductInterface;
use App\Repositories\Repository\Product\ProductRepository;
use App\Repositories\Interfaces\Api\Product\ProductsInterface;
use App\Repositories\Repository\Api\Product\ProductsRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(ProductsInterface::class, ProductsRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
