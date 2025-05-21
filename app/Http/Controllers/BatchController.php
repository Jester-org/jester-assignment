<?php
namespace App\Http\Controllers;
use App\Models\Batch;
use App\Models\Product;
use Illuminate\Http\Request;
class BatchController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::with('product')->get();
        if ($request->expectsJson()) {
            return response()->json($batches);
        }
        return view('batch.index', compact('batches'));
    }
    public function create()
    {
        $products = Product::all();
        return view('batch.create', compact('products'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_number' => 'required|string|max:255|unique:batches',
            'quantity' => 'required|integer|min:1',
            'received_at' => 'required|date',
        ]);
        $batch = Batch::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($batch, 201);
        }
        return redirect()->route('batches.index');
    }
    public function show(Request $request, Batch $batch)
    {
        $batch->load('product');
        if ($request->expectsJson()) {
            return response()->json($batch);
        }
        return view('batch.show', compact('batch'));
    }
    public function edit(Batch $batch)
    {
        $products = Product::all();
        return view('batch.edit', compact('batch', 'products'));
    }
    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_number' => 'required|string|max:255|unique:batches,batch_number,' . $batch->id,
            'quantity' => 'required|integer|min:1',
            'received_at' => 'required|date',
        ]);
        $batch->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($batch);
        }
        return redirect()->route('batches.index');
    }
    public function destroy(Request $request, Batch $batch)
    {
        $batch->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('batches.index');
    }
}

