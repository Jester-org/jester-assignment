<?php
namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with(['products', 'categories', 'freeItem'])->paginate(10);
        return view('promotion.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('promotion.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:discount,buy_get_free',
            'discount_type' => 'required_if:type,discount|in:fixed,percentage|nullable',
            'discount_value' => 'required_if:type,discount|numeric|min:0',
            'free_item_id' => 'required_if:type,buy_get_free|exists:products,id|nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'products' => 'array|nullable',
            'products.*' => 'exists:products,id',
            'categories' => 'array|nullable',
            'categories.*' => 'exists:categories,id',
        ]);

        DB::transaction(function () use ($validated) {
            $promotion = Promotion::create($validated);

            if (!empty($validated['products'])) {
                $promotion->products()->sync($validated['products']);
            }
            if (!empty($validated['categories'])) {
                $promotion->categories()->sync($validated['categories']);
            }

            if ($promotion->is_active && $promotion->type === 'discount') {
                foreach ($promotion->products as $product) {
                    $discountedPrice = $product->calculateDiscountedPrice();
                    $product->update(['unit_price' => $discountedPrice + $product->vat]);
                    Log::info('Applied promotion to product', ['product_id' => $product->id, 'promotion_id' => $promotion->id]);
                }
                foreach ($promotion->categories as $category) {
                    foreach ($category->products as $product) {
                        $discountedPrice = $product->calculateDiscountedPrice();
                        $product->update(['unit_price' => $discountedPrice + $product->vat]);
                        Log::info('Applied category promotion to product', ['product_id' => $product->id, 'promotion_id' => $promotion->id]);
                    }
                }
            }

            Log::info('Promotion created', ['id' => $promotion->id]);
        });

        return redirect()->route('promotions.index')->with('success', 'Promotion created successfully.');
    }

    public function show(Promotion $promotion)
    {
        $promotion->load(['products', 'categories', 'freeItem']);
        return view('promotion.show', compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::all();
        $categories = Category::all();
        $promotion->load(['products', 'categories', 'freeItem']);
        return view('promotion.edit', compact('promotion', 'products', 'categories'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'type' => 'required|in:discount,buy_get_free',
            'discount_type' => 'required_if:type,discount|in:fixed,percentage|nullable',
            'discount_value' => 'required_if:type,discount|numeric|min:0',
            'free_item_id' => 'required_if:type,buy_get_free|exists:products,id|nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'products' => 'array|nullable',
            'products.*' => 'exists:products,id',
            'categories' => 'array|nullable',
            'categories.*' => 'exists:categories,id',
        ]);

        DB::transaction(function () use ($validated, $promotion) {
            // Revert previous promotions
            if ($promotion->is_active && $promotion->type === 'discount') {
                foreach ($promotion->products as $product) {
                    $product->update(['unit_price' => $product->base_price + $product->vat]);
                    Log::info('Reverted promotion for product', ['product_id' => $product->id, 'promotion_id' => $promotion->id]);
                }
                foreach ($promotion->categories as $category) {
                    foreach ($category->products as $product) {
                        $product->update(['unit_price' => $product->base_price + $product->vat]);
                        Log::info('Reverted category promotion for product', ['product_id' => $product->id, 'promotion_id' => $promotion->id]);
                    }
                }
            }

            $promotion->update($validated);
            $promotion->products()->sync($validated['products'] ?? []);
            $promotion->categories()->sync($validated['categories'] ?? []);

            if ($promotion->is_active && $promotion->type === 'discount') {
                foreach ($promotion->products as $product) {
                    $discountedPrice = $product->calculateDiscountedPrice();
                    $product->update(['unit_price' => $discountedPrice + $product->vat]);
                    Log::info('Applied promotion to product', ['product_id' => $product->id, 'promotion_id' => $promotion->id]);
                }
                foreach ($promotion->categories as $category) {
                    foreach ($category->products as $product) {
                        $discountedPrice = $product->calculateDiscountedPrice();
                        $product->update(['unit_price' => $discountedPrice + $product->vat]);
                        Log::info('Applied category promotion to product', ['product_id' => $product->id, 'promotion_id' => $promotion->id]);
                    }
                }
            }

            Log::info('Promotion updated', ['id' => $promotion->id]);
        });

        return redirect()->route('promotions.index')->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        DB::transaction(function () use ($promotion) {
            if ($promotion->is_active && $promotion->type === 'discount') {
                foreach ($promotion->products as $product) {
                    $product->update(['unit_price' => $product->base_price + $product->vat]);
                    Log::info('Reverted promotion for product', ['product_id' => $product->id, 'promotion_id' => $promotion->id]);
                }
                foreach ($promotion->categories as $category) {
                    foreach ($category->products as $product) {
                        $product->update(['unit_price' => $product->base_price + $product->vat]);
                        Log::info('Reverted category promotion for product', ['product_id' => $product->id, 'promotion_id' => $promotion->id]);
                    }
                }
            }
            $promotion->delete();
        });

        return redirect()->route('promotions.index')->with('success', 'Promotion deleted successfully.');
    }
}