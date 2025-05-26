<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaxRateFactory extends Factory
{
    public function definition(): array
    {
        $rate = $this->faker->randomElement([5.00, 10.00, 20.00]);
        $name = $this->faker->randomElement(['Standard VAT', 'Reduced VAT', 'Zero VAT']);
        return [
            'name' => $name,
            'rate' => $rate,
            'display_name' => "$name ($rate%)",
        ];
    }
}