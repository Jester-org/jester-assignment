<?php
namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error fetching purchases: ' . $e->getMessage());
            return redirect()->route('purchases.index')->with('error', 'An error occurred while fetching purchases.');
        }
    }

    public function create()
    {
        try {
            $suppliers = Supplier::all();
            $users = User::all();
            $products = Product::with(['inventory', 'taxRate'])->get();
            $paymentMethods = PaymentMethod::all();
            return view('purchase.create', compact('suppliers', 'users', 'products', 'paymentMethods'));
        } catch (\Exception $e) {
            Log::error('Error loading create purchase form: ' . $e->getMessage());
            return redirect()->route('purchases.index')->with('error', 'An error occurred while loading the create form.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'user_id' => 'required|exists:users,id',
                'payment_method_id' => 'nullable|exists:payment_methods,id',
                'total_amount' => 'required|numeric|min:0',
                'purchase_date' => 'required|date',
                'status' => 'required|in:pending,completed,cancelled',
                'purchase_items' => 'required|array|min:1',
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
                    'payment_method_id' => $request->payment_method_id,
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating purchase: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the purchase.')->withInput();
        }
    }

    public function show(Request $request, Purchase $purchase)
    {
        try {
            $purchase->load(['supplier', 'user', 'purchaseItems.product']);
            if ($request->expectsJson()) {
                return response()->json($purchase);
            }
            return view('purchase.show', compact('purchase'));
        } catch (\Exception $e) {
            Log::error('Error showing purchase: ' . $e->getMessage());
            return redirect()->route('purchases.index')->with('error', 'An error occurred while fetching the purchase.');
        }
    }

    public function edit(Purchase $purchase)
    {
        try {
            $purchase->load('purchaseItems');
            $suppliers = Supplier::all();
            $users = User::all();
            $products = Product::with(['inventory', 'taxRate'])->get();
            $paymentMethods = PaymentMethod::all();
            return view('purchase.edit', compact('purchase', 'suppliers', 'users', 'products', 'paymentMethods'));
        } catch (\Exception $e) {
            Log::error('Error loading edit purchase form: ' . $e->getMessage());
            return redirect()->route('purchases.index')->with('error', 'An error occurred while loading the edit form.');
        }
    }

    public function update(Request $request, Purchase $purchase)
    {
        try {
            $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'user_id' => 'required|exists:users,id',
                'payment_method_id' => 'nullable|exists:payment_methods,id',
                'total_amount' => 'required|numeric|min:0',
                'purchase_date' => 'required|date',
                'status' => 'required|in:pending,completed,cancelled',
                'purchase_items' => 'required|array|min:1',
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
                    'payment_method_id' => $request->payment_method_id,
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating purchase: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the purchase.')->withInput();
        }
    }

    public function destroy(Request $request, Purchase $purchase)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error deleting purchase: ' . $e->getMessage(), [
                'purchase_id' => $purchase->id,
                'exception' => $e->getTraceAsString(),
            ]);
            return redirect()->route('purchases.index')->with('error', 'An error occurred while deleting the purchase: ' . $e->getMessage());
        }
    }
}