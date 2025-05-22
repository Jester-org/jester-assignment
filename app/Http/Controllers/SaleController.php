<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\User;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        // Eager load related models for performance
        $sales = Sale::with(['customer', 'user', 'saleItems.product'])->get();

        if ($request->expectsJson()) {
            return response()->json($sales);
        }
        return view('sale.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $users = User::all();
        $products = Product::with('inventory')->get();
        return view('sale.create', compact('customers', 'users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'sale_items' => 'nullable|array', // Changed to nullable
            'sale_items.*.product_id' => 'required_if:sale_items,array|exists:products,id',
            'sale_items.*.quantity' => 'required_if:sale_items,array|integer|min:1',
            'sale_items.*.unit_price' => 'required_if:sale_items,array|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'total_amount' => $request->total_amount,
                'sale_date' => $request->sale_date,
            ]);

            if (!empty($request->sale_items)) {
                foreach ($request->sale_items as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['quantity'] * $item['unit_price'],
                    ]);
                }
            }
        });

        if ($request->expectsJson()) {
            $sale = Sale::with('saleItems')->latest()->first();
            return response()->json($sale, 201);
        }
        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    public function show(Request $request, Sale $sale)
    {
        $sale->load(['customer', 'user', 'saleItems.product']);

        if ($request->expectsJson()) {
            return response()->json($sale);
        }
        return view('sale.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('saleItems'); // preload items for form

        $customers = Customer::all();
        $users = User::all();
        $products = Product::with('inventory')->get();

        return view('sale.edit', compact('sale', 'customers', 'users', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'sale_items' => 'nullable|array', // Changed to nullable
            'sale_items.*.product_id' => 'required_if:sale_items,array|exists:products,id',
            'sale_items.*.quantity' => 'required_if:sale_items,array|integer|min:1',
            'sale_items.*.unit_price' => 'required_if:sale_items,array|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $sale) {
            $sale->update([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'total_amount' => $request->total_amount,
                'sale_date' => $request->sale_date,
            ]);

            // Delete existing sale items to handle removed items
            $sale->saleItems()->delete();

            // Insert new/updated sale items if provided
            if (!empty($request->sale_items)) {
                foreach ($request->sale_items as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['quantity'] * $item['unit_price'],
                    ]);
                }
            }
        });

        if ($request->expectsJson()) {
            return response()->json($sale->load('saleItems'));
        }
        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(Request $request, Sale $sale)
    {
        $sale->delete();

        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}