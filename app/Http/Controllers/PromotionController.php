<?php
namespace App\Http\Controllers;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;
class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $promotions = Promotion::with('product')->get();
        if ($request->expectsJson()) {
            return response()->json($promotions);
        }
        return view('promotion.index', compact('promotions'));
    }
    public function create()
    {
        $products = Product::all();
        return view('promotion.create', compact('products'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        $promotion = Promotion::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($promotion, 201);
        }
        return redirect()->route('promotions.index');
    }
    public function show(Request $request, Promotion $promotion)
    {
        $promotion->load('product');
        if ($request->expectsJson()) {
            return response()->json($promotion);
        }
        return view('promotion.show', compact('promotion'));
    }
    public function edit(Promotion $promotion)
    {
        $products = Product::all();
        return view('promotion.edit', compact('promotion', 'products'));
    }
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        $promotion->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($promotion);
        }
        return redirect()->route('promotions.index');
    }
    public function destroy(Request $request, Promotion $promotion)
    {
        $promotion->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('promotions.index');
    }
}

