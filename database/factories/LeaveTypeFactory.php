<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Annual Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave', 'Unpaid Leave']),
            'description' => $this->faker->sentence(),
        ];
    }
}