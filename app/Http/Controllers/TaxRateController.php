<?php
namespace App\Http\Controllers;
use App\Models\TaxRate;
use Illuminate\Http\Request;
class TaxRateController extends Controller
{
    public function index(Request $request)
    {
        $taxRates = TaxRate::with('products')->get();
        if ($request->expectsJson()) {
            return response()->json($taxRates);
        }
        return view('tax_rate.index', compact('taxRates'));
    }
    public function create()
    {
        return view('tax_rate.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
        ]);
        $taxRate = TaxRate::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($taxRate, 201);
        }
        return redirect()->route('tax-rates.index');
    }
    public function show(Request $request, TaxRate $taxRate)
    {
        $taxRate->load('products');
        if ($request->expectsJson()) {
            return response()->json($taxRate);
        }
        return view('tax_rate.show', compact('taxRate'));
    }
    public function edit(TaxRate $taxRate)
    {
        return view('tax_rate.edit', compact('taxRate'));
    }
    public function update(Request $request, TaxRate $taxRate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
        ]);
        $taxRate->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($taxRate);
        }
        return redirect()->route('tax-rates.index');
    }
    public function destroy(Request $request, TaxRate $taxRate)
    {
        $taxRate->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('tax-rates.index');
    }
}

