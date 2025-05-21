<?php
namespace Database\Factories;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sale_id' => Sale::factory(),
            'amount' => $this->faker->randomFloat(2, 20, 2000),
            'transaction_date' => $this->faker->dateTimeThisYear(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
        ];
    }
}

