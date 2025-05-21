<?php
namespace App\Http\Controllers;
use App\Models\ExpiryDate;
use App\Models\Batch;
use Illuminate\Http\Request;
class ExpiryDateController extends Controller
{
    public function index(Request $request)
    {
        $expiryDates = ExpiryDate::with('batch')->get();
        if ($request->expectsJson()) {
            return response()->json($expiryDates);
        }
        return view('expiry_date.index', compact('expiryDates'));
    }
    public function create()
    {
        $batches = Batch::all();
        return view('expiry_date.create', compact('batches'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'expiry_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);
        $expiryDate = ExpiryDate::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($expiryDate, 201);
        }
        return redirect()->route('expiry_dates.index');
    }
    public function show(Request $request, ExpiryDate $expiryDate)
    {
        $expiryDate->load('batch');
        if ($request->expectsJson()) {
            return response()->json($expiryDate);
        }
        return view('expiry_date.show', compact('expiryDate'));
    }
    public function edit(ExpiryDate $expiryDate)
    {
        $batches = Batch::all();
        return view('expiry_date.edit', compact('expiryDate', 'batches'));
    }
    public function update(Request $request, ExpiryDate $expiryDate)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'expiry_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);
        $expiryDate->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($expiryDate);
        }
        return redirect()->route('expiry_dates.index');
    }
    public function destroy(Request $request, ExpiryDate $expiryDate)
    {
        $expiryDate->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('expiry_dates.index');
    }
}

