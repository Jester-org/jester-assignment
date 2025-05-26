<?php
namespace Database\Seeders;
use App\Models\Leave;
use Illuminate\Database\Seeder;
class LeaveSeeder extends Seeder
{
    public function run(): void
    {
        Leave::factory()->count(1)->create();
    }
}

