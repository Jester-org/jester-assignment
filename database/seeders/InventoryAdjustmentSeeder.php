<?php
namespace Database\Seeders;
use App\Models\InventoryAdjustment;
use Illuminate\Database\Seeder;
class InventoryAdjustmentSeeder extends Seeder
{
    public function run(): void
    {
        InventoryAdjustment::factory()->count(1)->create();
    }
}

