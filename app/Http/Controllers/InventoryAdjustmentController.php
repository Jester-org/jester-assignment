<?php
namespace App\Http\Controllers;
use App\Models\InventoryAdjustment;
use App\Models\Inventory;
use Illuminate\Http\Request;
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
        $inventoryAdjustment = InventoryAdjustment::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($inventoryAdjustment, 201);
        }
        return redirect()->route('inventory-adjustments.index');
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
        $inventoryAdjustment->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($inventoryAdjustment);
        }
        return redirect()->route('inventory-adjustments.index');
    }
    public function destroy(Request $request, InventoryAdjustment $inventoryAdjustment)
    {
        $inventoryAdjustment->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('inventory-adjustments.index');
    }
}

