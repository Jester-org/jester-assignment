<?php
namespace Database\Seeders;
use App\Models\ExpiryDate;
use Illuminate\Database\Seeder;
class ExpiryDateSeeder extends Seeder
{
    public function run(): void
    {
        ExpiryDate::factory()->count(5)->create();
    }
}

