<?php
namespace App\Http\Controllers;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $purchases = Purchase::with('supplier')->get();
        if ($request->expectsJson()) {
            return response()->json($purchases);
        }
        return view('purchase.index', compact('purchases'));
    }
    public function create()
    {
        $suppliers = Supplier::all();
        return view('purchase.create', compact('suppliers'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,cancelled',
        ]);
        $purchase = Purchase::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($purchase, 201);
        }
        return redirect()->route('purchases.index');
    }
    public function show(Request $request, Purchase $purchase)
    {
        $purchase->load('supplier', 'purchaseItems');
        if ($request->expectsJson()) {
            return response()->json($purchase);
        }
        return view('purchase.show', compact('purchase'));
    }
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::all();
        return view('purchase.edit', compact('purchase', 'suppliers'));
    }
    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,cancelled',
        ]);
        $purchase->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($purchase);
        }
        return redirect()->route('purchases.index');
    }
    public function destroy(Request $request, Purchase $purchase)
    {
        $purchase->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('purchases.index');
    }
}

