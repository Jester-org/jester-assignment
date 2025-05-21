<?php
namespace Database\Factories;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
class BatchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'batch_number' => $this->faker->unique()->bothify('BATCH-####'),
            'quantity' => $this->faker->numberBetween(10, 100),
            'received_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}

