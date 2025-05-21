<?php
namespace Database\Factories;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class AuditLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => $this->faker->randomElement(['create', 'update', 'delete', 'login', 'logout']),
            'description' => $this->faker->sentence(),
            'performed_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}

