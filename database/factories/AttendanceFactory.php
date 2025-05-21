<?php
namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        $checkIn = $this->faker->dateTimeThisMonth();
        return [
            'user_id' => User::factory(),
            'check_in' => $checkIn,
            'check_out' => $this->faker->dateTimeBetween($checkIn, $checkIn->modify('+8 hours'))->format('Y-m-d H:i:s'),
            'status' => $this->faker->randomElement(['present', 'absent', 'late']),
        ];
    }
}

