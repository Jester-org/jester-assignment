<?php
namespace Database\Factories;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
class PurchaseItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'purchase_id' => Purchase::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 50),
            'unit_price' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}

