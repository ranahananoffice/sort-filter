<?php
namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{

    public function run(): void
    {
        // Create 5 sellers
        $users = User::factory()->count(5)->create();

        // Create exactly 20 products (spread across random users)
        $products = Product::factory()->count(20)->make()->each(function ($product) use ($users) {
            $product->userId = $users->random()->id;
            $product->save();
        });

        // Create 20 random sales
        foreach (range(1, 20) as $i) {
            Sale::create([
                'userId'    => $users->random()->id,
                'productId' => $products->random()->id,
                'quantity'  => rand(1, 50),
                'price'     => rand(100, 1000),
            ]);
        }

        // Calculate top sellers
        $service = new \App\Services\TopSellerService();
        $saved   = $service->calculate(null, null, 20);

        if ($this->command) {
            $this->command->info("TopSellerService created/updated: " . count($saved));
        }
    }

}
