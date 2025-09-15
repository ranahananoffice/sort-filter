<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Product::count() < 1) {

             // Create 20 fake products
            Product::factory()->count(20)->create();

            // Get top 10 products by totalSales
            $topSellers = Product::orderByDesc('totalSales')->take(10)->get();

            // Mark them as top sellers
            foreach ($topSellers as $product) {
                $product->update(['isTopSeller' => true]);
            }
        }
    }
}
