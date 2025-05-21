<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class ReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'type' => $this->faker->randomElement(['sales', 'inventory', 'payment', 'user_activity']),
            'description' => $this->faker->paragraph(),
            'generated_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}

