<?php
namespace Database\Seeders;
use App\Models\Purchase;
use Illuminate\Database\Seeder;
class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        Purchase::factory()->count(5)->create();
    }
}

