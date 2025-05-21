<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\TaxRate;
use App\Models\Supplier;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with([
                'category', 
                'taxRate', 
                'inventory', 
                'suppliers'
            ])
            ->get()
            ->map(function ($product) {
                $product->low_stock = optional($product->inventory)->quantity <= $product->reorder_threshold;
                return $product;
            });

        if ($request->expectsJson()) {
            return response()->json($products);
        }

        return view('product.index', compact('products'));
    }

    public function create()
    {
        return view('product.create', [
            'categories' => Category::all(),
            'taxRates' => TaxRate::all(),
            'suppliers' => Supplier::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'required|string|max:255|unique:products',
            'unit_price' => 'required|numeric|min:0',
            'reorder_threshold' => 'required|integer|min:0',
            'supplier_ids' => 'nullable|array',
            'supplier_ids.*' => 'exists:suppliers,id',
        ]);

        $product = Product::create($validated);

        if ($request->has('supplier_ids')) {
            $product->suppliers()->attach($validated['supplier_ids']);
        }

        if ($request->expectsJson()) {
            return response()->json($product->load('suppliers'), 201);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function show(Request $request, Product $product)
    {
        $product->load(['category', 'taxRate', 'inventory', 'suppliers']);
        $product->low_stock = optional($product->inventory)->quantity <= $product->reorder_threshold;

        if ($request->expectsJson()) {
            return response()->json($product);
        }

        return view('product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('product.edit', [
            'product' => $product->load('suppliers'),
            'categories' => Category::all(),
            'taxRates' => TaxRate::all(),
            'suppliers' => Supplier::all()
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $product->id,
            'unit_price' => 'required|numeric|min:0',
            'reorder_threshold' => 'required|integer|min:0',
            'supplier_ids' => 'nullable|array',
            'supplier_ids.*' => 'exists:suppliers,id',
        ]);

        $product->update($validated);
        $product->suppliers()->sync($validated['supplier_ids'] ?? []);

        if ($request->expectsJson()) {
            return response()->json($product->load('suppliers'));
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