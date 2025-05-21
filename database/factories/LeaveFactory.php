<?php
namespace Database\Factories;

use App\Models\User;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d');
        $startDateTime = \Carbon\Carbon::parse($startDate);
        $endDate = $this->faker->dateTimeBetween($startDateTime->copy(), $startDateTime->copy()->addDays(7))->format('Y-m-d');

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'leave_type_id' => LeaveType::inRandomOrder()->first()->id ?? LeaveType::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'reason' => $this->faker->sentence(),
        ];
    }
}