<?php

namespace Database\Seeders;

use App\Models\TaxRate;
use Illuminate\Database\Seeder;

class TaxRateSeeder extends Seeder
{
    public function run(): void
    {
        TaxRate::factory()->create([
            'name' => 'Standard VAT',
            'rate' => 20.00,
            'display_name' => 'Standard VAT (20%)',
        ]);

        TaxRate::factory()->create([
            'name' => 'Reduced VAT',
            'rate' => 5.00,
            'display_name' => 'Reduced VAT (5%)',
        ]);

        TaxRate::factory()->create([
            'name' => 'Zero VAT',
            'rate' => 0.00,
            'display_name' => 'Zero VAT (0%)',
        ]);
    }
}