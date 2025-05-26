<?php
namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 50);
        $unit_price = $this->faker->randomFloat(2, 10, 500);
        return [
            'purchase_id' => Purchase::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'subtotal' => $quantity * $unit_price,
            'apply_tax' => $this->faker->boolean,
        ];
    }
}