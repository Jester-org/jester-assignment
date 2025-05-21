<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\User;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $sales = Sale::with(['customer', 'user', 'saleItems'])->get();
        if ($request->expectsJson()) {
            return response()->json($sales);
        }
        return view('sale.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $users = User::all();
        $products = Product::all();
        return view('sale.create', compact('customers', 'users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'sale_items' => 'required|array',
            'sale_items.*.product_id' => 'required|exists:products,id',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $sale = Sale::create([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user_id,
            'total_amount' => $request->total_amount,
            'sale_date' => $request->sale_date,
        ]);

        foreach ($request->sale_items as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json($sale->load('saleItems'), 201);
        }
        return redirect()->route('sales.index');
    }

    public function show(Request $request, Sale $sale)
    {
        $sale->load(['customer', 'user', 'saleItems']);
        if ($request->expectsJson()) {
            return response()->json($sale);
        }
        return view('sale.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $customers = Customer::all();
        $users = User::all();
        $products = Product::all();
        return view('sale.edit', compact('sale', 'customers', 'users', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'sale_items' => 'required|array',
            'sale_items.*.product_id' => 'required|exists:products,id',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $sale->update([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user_id,
            'total_amount' => $request->total_amount,
            'sale_date' => $request->sale_date,
        ]);

        $sale->saleItems()->delete();
        foreach ($request->sale_items as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json($sale->load('saleItems'));
        }
        return redirect()->route('sales.index');
    }

    public function destroy(Request $request, Sale $sale)
    {
        $sale->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('sales.index');
    }
}

