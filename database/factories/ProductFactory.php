<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $basePrice = $this->faker->randomFloat(2, 10, 1000); // Random price between 10.00 and 1000.00
        $taxRate = TaxRate::inRandomOrder()->first() ?? TaxRate::factory()->create();
        $vat = $basePrice * ($taxRate->rate / 100);
        $unitPrice = $basePrice + $vat;

        return [
            'category_id' => Category::inRandomOrder()->first() ?? Category::factory(),
            'tax_rate_id' => $taxRate->id,
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'sku' => 'SKU-' . $this->faker->unique()->dateTime->format('Ymd') . '-' . strtoupper($this->faker->lexify('????')),
            'base_price' => $basePrice,
            'vat' => $vat,
            'unit_price' => $unitPrice,
            'reorder_threshold' => $this->faker->numberBetween(10, 50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}