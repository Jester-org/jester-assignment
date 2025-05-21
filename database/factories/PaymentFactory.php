<?php
namespace Database\Factories;
use App\Models\Sale;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sale_id' => Sale::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'amount' => $this->faker->randomFloat(2, 20, 2000),
            'payment_date' => $this->faker->dateTimeThisYear(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
        ];
    }
}

