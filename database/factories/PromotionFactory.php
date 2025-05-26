<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(['discount', 'buy_get_free']);
        return [
            'type' => $type,
            'discount_type' => $type === 'discount' ? $this->faker->randomElement(['fixed', 'percentage']) : null,
            'discount_value' => $type === 'discount' ? $this->faker->randomFloat(2, 5, 50) : null,
            'free_item_id' => $type === 'buy_get_free' ? (Product::inRandomOrder()->first()?->id ?? Product::factory()->create()->id) : null,
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'is_active' => $this->faker->boolean(80),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Promotion $promotion) {
            if ($this->faker->boolean(70) && Product::exists()) {
                $products = Product::inRandomOrder()->take(rand(1, 3))->get();
                if ($products->isNotEmpty()) {
                    $promotion->products()->attach($products);
                }
            }
            if ($this->faker->boolean(70) && Category::exists()) {
                $categories = Category::inRandomOrder()->take(rand(1, 3))->get();
                if ($categories->isNotEmpty()) {
                    $promotion->categories()->attach($categories);
                }
            }
        });
    }
}