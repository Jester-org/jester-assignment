<?php
namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'purchaseItems.product']);
        if ($request->has('supplier_id') && $request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }
        $purchases = $query->get();
        $suppliers = Supplier::all();
        if ($request->expectsJson()) {
            return response()->json($purchases);
        }
        return view('purchase.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $users = User::all();
        $products = Product::with('inventory')->get();
        return view('purchase.create', compact('suppliers', 'users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'purchase_items' => 'required|array',
            'purchase_items.*.product_id' => 'required|exists:products,id',
            'purchase_items.*.quantity' => 'required|integer|min:1',
            'purchase_items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => $request->user_id,
                'total_amount' => $request->total_amount,
                'purchase_date' => $request->purchase_date,
                'status' => $request->status,
            ]);

            foreach ($request->purchase_items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => max(0, $subtotal),
                ]);

                // Update inventory
                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                if ($inventory) {
                    $inventory->quantity += $item['quantity'];
                    $inventory->last_updated = now();
                    $inventory->save();
                } else {
                    Inventory::create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'last_updated' => now(),
                    ]);
                }
            }
        });

        if ($request->expectsJson()) {
            $purchase = Purchase::with('purchaseItems')->latest()->first();
            return response()->json($purchase, 201);
        }
        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
    }

    public function show(Request $request, Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'purchaseItems.product']);
        if ($request->expectsJson()) {
            return response()->json($purchase);
        }
        return view('purchase.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $purchase->load('purchaseItems');
        $suppliers = Supplier::all();
        $users = User::all();
        $products = Product::with('inventory')->get();
        return view('purchase.edit', compact('purchase', 'suppliers', 'users', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'purchase_items' => 'required|array',
            'purchase_items.*.product_id' => 'required|exists:products,id',
            'purchase_items.*.quantity' => 'required|integer|min:1',
            'purchase_items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $purchase) {
            // Restore original quantities to inventory
            foreach ($purchase->purchaseItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->quantity -= $item->quantity;
                    $inventory->last_updated = now();
                    $inventory->save();
                }
            }

            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'user_id' => $request->user_id,
                'total_amount' => $request->total_amount,
                'purchase_date' => $request->purchase_date,
                'status' => $request->status,
            ]);

            $purchase->purchaseItems()->delete();

            foreach ($request->purchase_items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => max(0, $subtotal),
                ]);

                // Update inventory
                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                if ($inventory) {
                    $inventory->quantity += $item['quantity'];
                    $inventory->last_updated = now();
                    $inventory->save();
                } else {
                    Inventory::create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'last_updated' => now(),
                    ]);
                }
            }
        });

        if ($request->expectsJson()) {
            return response()->json($purchase->load('purchaseItems'));
        }
        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Request $request, Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            // Restore inventory quantities
            foreach ($purchase->purchaseItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->quantity -= $item->quantity;
                    $inventory->last_updated = now();
                    $inventory->save();
                }
            }
            $purchase->delete();
        });

        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}