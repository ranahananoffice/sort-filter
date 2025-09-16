<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        // ensure product & user exist; if not, create them (works during seeding)
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $userId  = $product->userId ?? User::inRandomOrder()->first()->id ?? User::factory()->create()->id;

        return [
            'userId'    => $userId,
            'productId' => $product->id,
            'quantity'   => $this->faker->numberBetween(1, 10),
            'price'      => $product->discountPrice ?: $product->originalPrice,
            'createdAt' => $this->faker->dateTimeBetween('-60 days', 'now'),
            'updatedAt' => now(),
        ];
    }
}
