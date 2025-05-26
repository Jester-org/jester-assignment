<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\TaxRate;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure at least one Category exists
        if (Category::count() === 0) {
            Category::factory()->create();
        }

        // Ensure at least one TaxRate exists
        if (TaxRate::count() === 0) {
            TaxRate::factory()->create([
                'name' => 'Standard VAT',
                'rate' => 20.0,
                'display_name' => 'Standard VAT (20%)',
            ]);
        }

        // Ensure at least one Supplier exists
        $suppliers = Supplier::all();
        if ($suppliers->isEmpty()) {
            $suppliers = Supplier::factory()->count(1)->create();
        }

        // Create products
        $products = Product::factory()->count(1)->create();

        foreach ($products as $product) {
            // Attach random suppliers
            $maxSuppliers = min(3, $suppliers->count());
            $count = $suppliers->count() > 1 ? rand(1, $maxSuppliers) : 1;
            $randomSuppliers = $suppliers->random($count);
            $product->suppliers()->attach($randomSuppliers);

            // Create inventory
            Inventory::firstOrCreate(
                ['product_id' => $product->id],
                Inventory::factory()->raw(['product_id' => $product->id])
            );
        }
    }
}