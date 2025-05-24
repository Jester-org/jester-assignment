<?php
namespace App\Http\Controllers;

use App\Models\SaleItem;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $saleItem = DB::transaction(function () use ($request) {
            // Validate stock availability
            $inventory = Inventory::where('product_id', $request->product_id)->first();
            if (!$inventory || $inventory->quantity < $request->quantity) {
                throw new \Exception("Insufficient stock for product ID {$request->product_id}. Available: " . ($inventory ? $inventory->quantity : 0));
            }

            // Create sale item
            $saleItem = SaleItem::create([
                'sale_id' => $request->sale_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'subtotal' => $request->quantity * $request->unit_price,
            ]);

            // Update inventory
            $inventory->quantity -= $request->quantity;
            $inventory->last_updated = now();
            $inventory->save();

            return $saleItem;
        });

        if ($request->expectsJson()) {
            return response()->json($saleItem, 201);
        }
        return redirect()->route('sale-items.index')->with('success', 'Sale item created successfully.');
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

        $saleItem = DB::transaction(function () use ($request, $saleItem) {
            // Restore original quantity to inventory
            $inventory = Inventory::where('product_id', $saleItem->product_id)->first();
            if ($inventory) {
                $inventory->quantity += $saleItem->quantity;
                $inventory->last_updated = now();
                $inventory->save();
            }

            // Validate new stock availability
            $inventory = Inventory::where('product_id', $request->product_id)->first();
            if (!$inventory || $inventory->quantity < $request->quantity) {
                throw new \Exception("Insufficient stock for product ID {$request->product_id}. Available: " . ($inventory ? $inventory->quantity : 0));
            }

            // Update sale item
            $saleItem->update([
                'sale_id' => $request->sale_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'subtotal' => $request->quantity * $request->unit_price,
            ]);

            // Update inventory
            $inventory->quantity -= $request->quantity;
            $inventory->last_updated = now();
            $inventory->save();

            return $saleItem;
        });

        if ($request->expectsJson()) {
            return response()->json($saleItem);
        }
        return redirect()->route('sale-items.index')->with('success', 'Sale item updated successfully.');
    }

    public function destroy(Request $request, SaleItem $saleItem)
    {
        DB::transaction(function () use ($saleItem) {
            // Restore inventory quantity
            $inventory = Inventory::where('product_id', $saleItem->product_id)->first();
            if ($inventory) {
                $inventory->quantity += $saleItem->quantity;
                $inventory->last_updated = now();
                $inventory->save();
            }

            $saleItem->delete();
        });

        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('sale-items.index')->with('success', 'Sale item deleted successfully.');
    }
}