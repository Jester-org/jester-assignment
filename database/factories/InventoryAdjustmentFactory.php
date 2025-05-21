<?php
namespace Database\Factories;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;
class InventoryAdjustmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'inventory_id' => Inventory::factory(),
            'adjustment_type' => $this->faker->randomElement(['addition', 'reduction']),
            'quantity' => $this->faker->numberBetween(1, 50),
            'reason' => $this->faker->sentence,
            'adjustment_date' => $this->faker->dateTimeThisYear(),
        ];
    }
}

