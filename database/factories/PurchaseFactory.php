<?php
namespace Database\Factories;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
class PurchaseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'purchase_date' => $this->faker->dateTimeThisYear(),
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }
}

