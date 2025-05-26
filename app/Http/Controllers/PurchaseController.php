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
use Illuminate\Support\Facades\Log;

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
        $products = Product::with(['inventory', 'taxRate'])->get();
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
            'purchase_items.*.apply_tax' => 'boolean',
            'purchase_items.*.discount' => 'numeric|min:0|max:100',
        ]);

        $purchase = DB::transaction(function () use ($request) {
            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => $request->user_id,
                'total_amount' => $request->total_amount,
                'purchase_date' => $request->purchase_date,
                'status' => $request->status,
            ]);

            foreach ($request->purchase_items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $applyTax = isset($item['apply_tax']) && $item['apply_tax'];
                $unitPrice = $applyTax ? $product->unit_price : $product->base_price;
                $discount = $item['discount'] ?? 0;
                if ($discount > 0) {
                    $unitPrice = $unitPrice * (1 - $discount / 100);
                }
                $subtotal = $item['quantity'] * $unitPrice;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => max(0, $subtotal),
                    'apply_tax' => $applyTax,
                    'discount' => $discount,
                ]);

                if ($request->status === 'completed') {
                    $inventory = Inventory::firstOrCreate(
                        ['product_id' => $item['product_id']],
                        ['quantity' => 0, 'last_updated' => now()]
                    );
                    $inventory->quantity += $item['quantity'];
                    $inventory->last_updated = now();
                    $inventory->save();
                    Log::info('Inventory updated', [
                        'product_id' => $item['product_id'],
                        'quantity' => $inventory->quantity,
                    ]);

                    if (!$product->suppliers()->where('supplier_id', $request->supplier_id)->exists()) {
                        $product->suppliers()->attach($request->supplier_id, ['created_at' => now(), 'updated_at' => now()]);
                        Log::info('Supplier attached', [
                            'product_id' => $item['product_id'],
                            'supplier_id' => $request->supplier_id,
                        ]);
                    }
                }
            }

            return $purchase;
        });

        if ($request->expectsJson()) {
            $purchase->load('purchaseItems');
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
        $products = Product::with(['inventory', 'taxRate'])->get();
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
            'purchase_items.*.apply_tax' => 'boolean',
            'purchase_items.*.discount' => 'numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $purchase) {
            $originalSupplierId = $purchase->supplier_id;

            if ($purchase->status === 'completed') {
                foreach ($purchase->purchaseItems as $item) {
                    $inventory = Inventory::where('product_id', $item->product_id)->first();
                    if ($inventory) {
                        $inventory->quantity -= $item->quantity;
                        if ($inventory->quantity < 0) {
                            Log::warning('Negative inventory prevented', [
                                'product_id' => $item->product_id,
                                'quantity' => $inventory->quantity,
                            ]);
                            $inventory->quantity = 0;
                        }
                        $inventory->last_updated = now();
                        $inventory->save();
                        Log::info('Inventory reversed', [
                            'product_id' => $item->product_id,
                            'quantity' => $inventory->quantity,
                        ]);
                    }

                    $product = Product::find($item->product_id);
                    if ($product) {
                        $otherCompletedPurchases = Purchase::where('status', 'completed')
                            ->where('id', '!=', $purchase->id)
                            ->where('supplier_id', $originalSupplierId)
                            ->whereHas('purchaseItems', function ($query) use ($item) {
                                $query->where('product_id', $item->product_id);
                            })->exists();

                        if (!$otherCompletedPurchases) {
                            $product->suppliers()->detach($originalSupplierId);
                            Log::info('Supplier detached', [
                                'product_id' => $item->product_id,
                                'supplier_id' => $originalSupplierId,
                            ]);
                        }
                    }
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
                $product = Product::findOrFail($item['product_id']);
                $applyTax = isset($item['apply_tax']) && $item['apply_tax'];
                $unitPrice = $applyTax ? $product->unit_price : $product->base_price;
                $discount = $item['discount'] ?? 0;
                if ($discount > 0) {
                    $unitPrice = $unitPrice * (1 - $discount / 100);
                }
                $subtotal = $item['quantity'] * $unitPrice;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => max(0, $subtotal),
                    'apply_tax' => $applyTax,
                    'discount' => $discount,
                ]);

                if ($request->status === 'completed') {
                    $inventory = Inventory::firstOrCreate(
                        ['product_id' => $item['product_id']],
                        ['quantity' => 0, 'last_updated' => now()]
                    );
                    $inventory->quantity += $item['quantity'];
                    $inventory->last_updated = now();
                    $inventory->save();
                    Log::info('Inventory updated', [
                        'product_id' => $item['product_id'],
                        'quantity' => $inventory->quantity,
                    ]);

                    if (!$product->suppliers()->where('supplier_id', $request->supplier_id)->exists()) {
                        $product->suppliers()->attach($request->supplier_id, ['created_at' => now(), 'updated_at' => now()]);
                        Log::info('Supplier attached', [
                            'product_id' => $item['product_id'],
                            'supplier_id' => $request->supplier_id,
                        ]);
                    }
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
            if ($purchase->status === 'completed') {
                foreach ($purchase->purchaseItems as $item) {
                    $inventory = Inventory::where('product_id', $item->product_id)->first();
                    if ($inventory) {
                        $inventory->quantity -= $item->quantity;
                        if ($inventory->quantity < 0) {
                            Log::warning('Negative inventory prevented', [
                                'product_id' => $item->product_id,
                                'quantity' => $inventory->quantity,
                            ]);
                            $inventory->quantity = 0;
                        }
                        $inventory->last_updated = now();
                        $inventory->save();
                        Log::info('Inventory reversed', [
                            'product_id' => $item->product_id,
                            'quantity' => $inventory->quantity,
                        ]);
                    }

                    $product = Product::find($item->product_id);
                    if ($product) {
                        $otherCompletedPurchases = Purchase::where('status', 'completed')
                            ->where('id', '!=', $purchase->id)
                            ->where('supplier_id', $purchase->supplier_id)
                            ->whereHas('purchaseItems', function ($query) use ($item) {
                                $query->where('product_id', $item->product_id);
                            })->exists();

                        if (!$otherCompletedPurchases) {
                            $product->suppliers()->detach($purchase->supplier_id);
                            Log::info('Supplier detached', [
                                'product_id' => $item->product_id,
                                'supplier_id' => $purchase->supplier_id,
                            ]);
                        }
                    }
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