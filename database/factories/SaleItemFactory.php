<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleItemFactory extends Factory
{
    public function definition(): array
    {
        $unit_price = $this->faker->randomFloat(2, 5, 100);
        $quantity = $this->faker->numberBetween(1, 10);
        return [
            'sale_id' => Sale::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'subtotal' => $unit_price * $quantity,
        ];
    }
}