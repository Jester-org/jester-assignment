<?php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\User;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'saleItems.product']);
        if ($request->has('customer_id') && $request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }
        $sales = $query->get();
        $customers = Customer::all();
        if ($request->expectsJson()) {
            return response()->json($sales);
        }
        return view('sale.index', compact('sales', 'customers'));
    }

    public function create()
    {
        $customers = Customer::all();
        $users = User::all();
        $products = Product::with('inventory')->get();
        $paymentMethods = PaymentMethod::all();
        return view('sale.create', compact('customers', 'users', 'products', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'total_amount' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'sale_items' => 'required|array',
            'sale_items.*.product_id' => 'required|exists:products,id',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.unit_price' => 'required|numeric|min:0',
            'sale_items.*.discount' => 'nullable|numeric|min:0',
            'sale_items.*.discount_type' => 'required|in:fixed,percentage',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->sale_items as $item) {
                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                if (!$inventory || $inventory->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product ID {$item['product_id']}. Available: " . ($inventory ? $inventory->quantity : 0));
                }
            }

            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'payment_method_id' => $request->payment_method_id,
                'total_amount' => $request->total_amount,
                'sale_date' => $request->sale_date,
            ]);

            foreach ($request->sale_items as $item) {
                $baseAmount = $item['quantity'] * $item['unit_price'];
                $discountValue = $item['discount'] ?? 0;
                $subtotal = $baseAmount;
                if ($discountValue > 0) {
                    if ($item['discount_type'] === 'percentage') {
                        $subtotal = $baseAmount * (1 - $discountValue / 100);
                    } else {
                        $subtotal = $baseAmount - $discountValue;
                    }
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => max(0, $subtotal),
                    'discount' => $discountValue,
                    'discount_type' => $item['discount_type'],
                ]);

                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                $inventory->quantity -= $item['quantity'];
                $inventory->last_updated = now();
                $inventory->save();
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
        $sale->load('saleItems');
        $customers = Customer::all();
        $users = User::all();
        $products = Product::with('inventory')->get();
        $paymentMethods = PaymentMethod::all();
        return view('sale.edit', compact('sale', 'customers', 'users', 'products', 'paymentMethods'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'total_amount' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'sale_items' => 'required|array',
            'sale_items.*.product_id' => 'required|exists:products,id',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.unit_price' => 'required|numeric|min:0',
            'sale_items.*.discount' => 'nullable|numeric|min:0',
            'sale_items.*.discount_type' => 'required|in:fixed,percentage',
        ]);

        DB::transaction(function () use ($request, $sale) {
            foreach ($sale->saleItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->quantity += $item->quantity;
                    $inventory->last_updated = now();
                    $inventory->save();
                }
            }

            foreach ($request->sale_items as $item) {
                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                if (!$inventory || $inventory->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product ID {$item['product_id']}. Available: " . ($inventory ? $inventory->quantity : 0));
                }
            }

            $sale->update([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'payment_method_id' => $request->payment_method_id,
                'total_amount' => $request->total_amount,
                'sale_date' => $request->sale_date,
            ]);

            $sale->saleItems()->delete();

            foreach ($request->sale_items as $item) {
                $baseAmount = $item['quantity'] * $item['unit_price'];
                $discountValue = $item['discount'] ?? 0;
                $subtotal = $baseAmount;
                if ($discountValue > 0) {
                    if ($item['discount_type'] === 'percentage') {
                        $subtotal = $baseAmount * (1 - $discountValue / 100);
                    } else {
                        $subtotal = $baseAmount - $discountValue;
                    }
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => max(0, $subtotal),
                    'discount' => $discountValue,
                    'discount_type' => $item['discount_type'],
                ]);

                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                $inventory->quantity -= $item['quantity'];
                $inventory->last_updated = now();
                $inventory->save();
            }
        });

        if ($request->expectsJson()) {
            return response()->json($sale->load('saleItems'));
        }
        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(Request $request, Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            foreach ($sale->saleItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->quantity += $item->quantity;
                    $inventory->last_updated = now();
                    $inventory->save();
                }
            }
            $sale->delete();
        });

        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    public function checkStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $inventory = Inventory::where('product_id', $request->product_id)->first();
        $available = $inventory ? $inventory->quantity : 0;

        if (!$inventory || $available <= 0) {
            return response()->json(['error' => 'The product is out of stock.'], 422);
        }
        if ($available < $request->quantity) {
            return response()->json(['error' => "Insufficient stock. Available: $available."], 422);
        }

        return response()->json(['success' => true, 'available' => $available]);
    }
}