<?php
namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        Purchase::factory()
            ->count(10)
            ->has(PurchaseItem::factory()->count(1))
            ->create();
    }
}