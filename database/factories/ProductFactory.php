<?php
namespace Database\Factories;
use App\Models\Category;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'tax_rate_id' => TaxRate::factory(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'barcode' => $this->faker->unique()->ean13(),
            'unit_price' => $this->faker->randomFloat(2, 10, 1000),
            'reorder_threshold' => $this->faker->numberBetween(5, 20),
        ];
    }
}

