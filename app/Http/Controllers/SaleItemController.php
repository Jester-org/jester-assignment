<?php

namespace App\Http\Controllers;

use App\Models\SaleItem;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleItemController extends Controller
{
    public function index(Request $request)
    {
        $saleItems = SaleItem::with(['sale', 'product'])->get();
        if ($request->expectsJson()) {
            return response()->json($saleItems);
        }
        return view('sale_item.index', compact('saleItems'));
    }

    public function create()
    {
        $sales = Sale::all();
        $products = Product::all();
        return view('sale_item.create', compact('sales', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $saleItem = SaleItem::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($saleItem, 201);
        }
        return redirect()->route('sale-items.index');
    }

    public function show(Request $request, SaleItem $saleItem)
    {
        $saleItem->load(['sale', 'product']);
        if ($request->expectsJson()) {
            return response()->json($saleItem);
        }
        return view('sale_item.show', compact('saleItem'));
    }

    public function edit(SaleItem $saleItem)
    {
        $sales = Sale::all();
        $products = Product::all();
        return view('sale_item.edit', compact('saleItem', 'sales', 'products'));
    }

    public function update(Request $request, SaleItem $saleItem)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $saleItem->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($saleItem);
        }
        return redirect()->route('sale-items.index');
    }

    public function destroy(Request $request, SaleItem $saleItem)
    {
        $saleItem->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('sale-items.index');
    }
}