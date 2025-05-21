<?php
namespace Database\Factories;
use App\Models\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;
class ExpiryDateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'batch_id' => Batch::factory(),
            'expiry_date' => $this->faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'notes' => $this->faker->sentence(),
        ];
    }
}

