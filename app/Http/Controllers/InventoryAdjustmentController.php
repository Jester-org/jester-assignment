<?php
namespace App\Http\Controllers;

use App\Models\InventoryAdjustment;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $inventoryAdjustments = InventoryAdjustment::with('inventory.product')->get();
        if ($request->expectsJson()) {
            return response()->json($inventoryAdjustments);
        }
        return view('inventory_adjustment.index', compact('inventoryAdjustments'));
    }

    public function create()
    {
        $inventories = Inventory::with('product')->get();
        return view('inventory_adjustment.create', compact('inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'adjustment_type' => 'required|in:addition,reduction',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
            'adjustment_date' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            $inventory = Inventory::findOrFail($request->inventory_id);
            if ($request->adjustment_type === 'reduction' && $inventory->quantity < $request->quantity) {
                throw new \Exception("Insufficient stock for reduction. Available: {$inventory->quantity}.");
            }

            $inventoryAdjustment = InventoryAdjustment::create($request->all());

            // Update inventory
            $inventory->quantity += ($request->adjustment_type === 'addition' ? $request->quantity : -$request->quantity);
            $inventory->last_updated = now();
            $inventory->save();
        });

        if ($request->expectsJson()) {
            return response()->json($inventoryAdjustment, 201);
        }
        return redirect()->route('inventory-adjustments.index')->with('success', 'Adjustment created successfully.');
    }

    public function show(Request $request, InventoryAdjustment $inventoryAdjustment)
    {
        $inventoryAdjustment->load('inventory.product');
        if ($request->expectsJson()) {
            return response()->json($inventoryAdjustment);
        }
        return view('inventory_adjustment.show', compact('inventoryAdjustment'));
    }

    public function edit(InventoryAdjustment $inventoryAdjustment)
    {
        $inventories = Inventory::with('product')->get();
        return view('inventory_adjustment.edit', compact('inventoryAdjustment', 'inventories'));
    }

    public function update(Request $request, InventoryAdjustment $inventoryAdjustment)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'adjustment_type' => 'required|in:addition,reduction',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
            'adjustment_date' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $inventoryAdjustment) {
            $inventory = Inventory::findOrFail($request->inventory_id);

            // Revert previous adjustment
            $inventory->quantity += ($inventoryAdjustment->adjustment_type === 'addition' ? -$inventoryAdjustment->quantity : $inventoryAdjustment->quantity);

            // Validate new adjustment
            if ($request->adjustment_type === 'reduction' && $inventory->quantity < $request->quantity) {
                throw new \Exception("Insufficient stock for reduction. Available: {$inventory->quantity}.");
            }

            $inventoryAdjustment->update($request->all());

            // Apply new adjustment
            $inventory->quantity += ($request->adjustment_type === 'addition' ? $request->quantity : -$request->quantity);
            $inventory->last_updated = now();
            $inventory->save();
        });

        if ($request->expectsJson()) {
            return response()->json($inventoryAdjustment);
        }
        return redirect()->route('inventory-adjustments.index')->with('success', 'Adjustment updated successfully.');
    }

    public function destroy(Request $request, InventoryAdjustment $inventoryAdjustment)
    {
        DB::transaction(function () use ($inventoryAdjustment) {
            $inventory = Inventory::findOrFail($inventoryAdjustment->inventory_id);
            // Revert adjustment
            $inventory->quantity += ($inventoryAdjustment->adjustment_type === 'addition' ? -$inventoryAdjustment->quantity : $inventoryAdjustment->quantity);
            $inventory->last_updated = now();
            $inventory->save();

            $inventoryAdjustment->delete();
        });

        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('inventory-adjustments.index')->with('success', 'Adjustment deleted successfully.');
    }
}