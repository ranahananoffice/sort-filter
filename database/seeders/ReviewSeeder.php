<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        foreach ($products as $product) {
            // each product gets between 2–8 reviews
            $reviewCount = rand(2, 8);

            for ($i = 0; $i < $reviewCount; $i++) {
                Review::create([
                    'userId'    => $users->random()->id,
                    'productId' => $product->id,
                    'rating'    => rand(1, 5), // 1–5 stars
                    'comment'   => fake()->sentence(),
                ]);
            }
        }
    }
}
