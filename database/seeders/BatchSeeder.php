<?php
namespace Database\Seeders;
use App\Models\Batch;
use Illuminate\Database\Seeder;
class BatchSeeder extends Seeder
{
    public function run(): void
    {
        Batch::factory()->count(1)->create();
    }
}

