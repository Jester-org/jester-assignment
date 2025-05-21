<?php
namespace App\Http\Controllers;
use App\Models\Supplier;
use Illuminate\Http\Request;
class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::with('purchases')->get();
        if ($request->expectsJson()) {
            return response()->json($suppliers);
        }
        return view('supplier.index', compact('suppliers'));
    }
    public function create()
    {
        return view('supplier.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        $supplier = Supplier::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($supplier, 201);
        }
        return redirect()->route('suppliers.index');
    }
    public function show(Request $request, Supplier $supplier)
    {
        $supplier->load('purchases');
        if ($request->expectsJson()) {
            return response()->json($supplier);
        }
        return view('supplier.show', compact('supplier'));
    }
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        $supplier->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($supplier);
        }
        return redirect()->route('suppliers.index');
    }
    public function destroy(Request $request, Supplier $supplier)
    {
        $supplier->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('suppliers.index');
    }
}

