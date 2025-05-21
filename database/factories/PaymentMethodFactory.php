<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class PaymentMethodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Credit Card', 'Debit Card', 'Cash', 'Bank Transfer', 'Mobile Payment']),
            'description' => $this->faker->sentence(),
        ];
    }
}

