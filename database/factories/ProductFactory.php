<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $images = [
            config('app.url') . '/image/image1.jpg',
            config('app.url') . '/image/image2.jpg',
            config('app.url') . '/image/image3.jpg',
            config('app.url') . '/image/image4.jpg',
            config('app.url') . '/image/image5.jpg',
        ];

        return [
            'image'         => $this->faker->randomElement($images),
            'title'         => $this->faker->words(3, true),
            'description'   => $this->faker->words(13, true),
            'originalPrice' => $this->faker->randomFloat(2, 100, 1000),
            'discountPrice' => $this->faker->optional()->randomFloat(2, 50, 999), // sometimes null
            'totalSales'    => $this->faker->numberBetween(0, 5000),
            'isTopSeller'   => false,
            'tag'           => $this->faker->randomElement([
                'Nike', 'Adidas', 'Puma', 'Reebok', 'Gucci', 'Prada', 'Zara', 'Levis', 'H&M',
            ]),
        ];

    }
}
