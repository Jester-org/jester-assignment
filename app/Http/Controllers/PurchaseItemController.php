<?php
namespace App\Http\Controllers;
use App\Models\PurchaseItem;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
class PurchaseItemController extends Controller
{
    public function index(Request $request)
    {
        $purchaseItems = PurchaseItem::with(['purchase.supplier', 'product'])->get();
        if ($request->expectsJson()) {
            return response()->json($purchaseItems);
        }
        return view('purchase_item.index', compact('purchaseItems'));
    }
    public function create()
    {
        $purchases = Purchase::with('supplier')->get();
        $products = Product::all();
        return view('purchase_item.create', compact('purchases', 'products'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);
        $purchaseItem = PurchaseItem::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($purchaseItem, 201);
        }
        return redirect()->route('purchase-items.index');
    }
    public function show(Request $request, PurchaseItem $purchaseItem)
    {
        $purchaseItem->load(['purchase.supplier', 'product']);
        if ($request->expectsJson()) {
            return response()->json($purchaseItem);
        }
        return view('purchase_item.show', compact('purchaseItem'));
    }
    public function edit(PurchaseItem $purchaseItem)
    {
        $purchases = Purchase::with('supplier')->get();
        $products = Product::all();
        return view('purchase_item.edit', compact('purchaseItem', 'purchases', 'products'));
    }
    public function update(Request $request, PurchaseItem $purchaseItem)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);
        $purchaseItem->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($purchaseItem);
        }
        return redirect()->route('purchase-items.index');
    }
    public function destroy(Request $request, PurchaseItem $purchaseItem)
    {
        $purchaseItem->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('purchase-items.index');
    }
}

