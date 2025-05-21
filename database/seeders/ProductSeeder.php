<?php
namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // First ensure suppliers exist
        $suppliers = Supplier::all();
        
        if ($suppliers->isEmpty()) {
            $suppliers = Supplier::factory()->count(5)->create();
        }

        // Create products
        $products = Product::factory()->count(5)->create();

        // Attach suppliers to products
        foreach ($products as $product) {
            $maxSuppliers = min(3, $suppliers->count());
            $count = $suppliers->count() > 1 ? rand(1, $maxSuppliers) : 1;
            $randomSuppliers = $suppliers->random($count);
            
            $product->suppliers()->attach($randomSuppliers);
        }
    }
}