<?php
namespace Database\Factories;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
class PromotionFactory extends Factory
{
    public function definition(): array
    {
        $start_date = $this->faker->dateTimeBetween('-1 month', 'now');
        $end_date = $this->faker->dateTimeBetween('now', '+1 month');
        return [
            'product_id' => Product::factory(),
            'discount_percentage' => $this->faker->randomFloat(2, 5, 50),
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }
}

