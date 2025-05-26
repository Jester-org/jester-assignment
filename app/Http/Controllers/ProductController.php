<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\TaxRate;
use App\Models\Supplier;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with([
                'category',
                'taxRate',
                'inventory',
                'suppliers',
                'promotions'
            ])
            ->paginate(10)
            ->through(function ($product) {
                $product->low_stock = $product->inventory
                    ? $product->inventory->quantity <= $product->reorder_threshold
                    : true;
                $product->stock_quantity = $product->inventory ? $product->inventory->quantity : 0;
                return $product;
            });

        if ($request->expectsJson()) {
            return response()->json($products);
        }

        return view('product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $taxRates = TaxRate::all()->map(function ($taxRate) {
            $taxRate->display_name = "{$taxRate->name} ({$taxRate->rate}%)";
            return $taxRate;
        });

        return view('product.create', compact('categories', 'taxRates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'reorder_threshold' => 'required|integer|min:0',
        ]);

        $taxRate = TaxRate::findOrFail($validated['tax_rate_id']);
        $basePrice = $validated['base_price'];
        $validated['vat'] = $basePrice * ($taxRate->rate / 100);
        $validated['unit_price'] = $basePrice + $validated['vat'];

        $product = Product::create($validated);

        // Apply any applicable promotions
        $discountedPrice = $product->calculateDiscountedPrice();
        $product->update(['unit_price' => $discountedPrice + $product->vat]);

        if ($request->expectsJson()) {
            return response()->json($product->load('inventory', 'suppliers'), 201);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function show(Request $request, Product $product)
    {
        $product->load(['category', 'taxRate', 'inventory', 'suppliers', 'promotions']);
        $product->low_stock = $product->inventory
            ? $product->inventory->quantity <= $product->reorder_threshold
            : true;
        $product->stock_quantity = $product->inventory ? $product->inventory->quantity : 0;

        if ($request->expectsJson()) {
            return response()->json($product);
        }

        return view('product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $taxRates = TaxRate::all()->map(function ($taxRate) {
            $taxRate->display_name = "{$taxRate->name} ({$taxRate->rate}%)";
            return $taxRate;
        });

        $product->load(['category', 'taxRate']);

        return view('product.edit', compact('product', 'categories', 'taxRates'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'base_price' => 'required|numeric|min:0',
            'reorder_threshold' => 'required|integer|min:0',
        ]);

        $taxRate = TaxRate::findOrFail($validated['tax_rate_id']);
        $validated['vat'] = $validated['base_price'] * ($taxRate->rate / 100);
        $validated['unit_price'] = $validated['base_price'] + $validated['vat'];

        Log::info('Updating product', [
            'id' => $product->id,
            'validated' => $validated
        ]);

        $product->update($validated);

        // Reapply promotions
        $discountedPrice = $product->calculateDiscountedPrice();
        $product->update(['unit_price' => $discountedPrice + $product->vat]);

        Log::info('Product updated', [
            'id' => $product->id,
            'vat' => $product->vat,
            'unit_price' => $product->unit_price
        ]);

        if ($request->expectsJson()) {
            return response()->json($product->load('inventory', 'suppliers'));
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete();

        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}