<?php
namespace App\Http\Controllers;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $inventories = Inventory::with('product')->get();
        if ($request->expectsJson()) {
            return response()->json($inventories);
        }
        return view('inventory.index', compact('inventories'));
    }

    public function create()
    {
        $products = Product::all();
        return view('inventory.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'last_updated' => 'nullable|date',
        ]);
        $inventory = Inventory::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($inventory, 201);
        }
        return redirect()->route('inventories.index');
    }

    public function show(Request $request, Inventory $inventory)
    {
        $inventory->load('product');
        if ($request->expectsJson()) {
            return response()->json($inventory);
        }
        return view('inventory.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        $products = Product::all();
        return view('inventory.edit', compact('inventory', 'products'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'last_updated' => 'nullable|date',
        ]);
        $inventory->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($inventory);
        }
        return redirect()->route('inventories.index');
    }

    public function destroy(Request $request, Inventory $inventory)
    {
        $inventory->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('inventories.index');
    }
}