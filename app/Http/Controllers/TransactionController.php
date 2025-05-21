<?php
namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Sale;
use Illuminate\Http\Request;
class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with('sale.customer')->get();
        if ($request->expectsJson()) {
            return response()->json($transactions);
        }
        return view('transaction.index', compact('transactions'));
    }
    public function create()
    {
        $sales = Sale::with('customer')->get();
        return view('transaction.create', compact('sales'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'status' => 'required|in:pending,completed,failed',
        ]);
        $transaction = Transaction::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($transaction, 201);
        }
        return redirect()->route('transactions.index');
    }
    public function show(Request $request, Transaction $transaction)
    {
        $transaction->load('sale.customer');
        if ($request->expectsJson()) {
            return response()->json($transaction);
        }
        return view('transaction.show', compact('transaction'));
    }
    public function edit(Transaction $transaction)
    {
        $sales = Sale::with('customer')->get();
        return view('transaction.edit', compact('transaction', 'sales'));
    }
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'status' => 'required|in:pending,completed,failed',
        ]);
        $transaction->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($transaction);
        }
        return redirect()->route('transactions.index');
    }
    public function destroy(Request $request, Transaction $transaction)
    {
        $transaction->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('transactions.index');
    }
}

