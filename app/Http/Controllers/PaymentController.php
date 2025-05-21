<?php
namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['sale.customer', 'paymentMethod'])->get();
        if ($request->expectsJson()) {
            return response()->json($payments);
        }
        return view('payment.index', compact('payments'));
    }
    public function create()
    {
        $sales = Sale::with('customer')->get();
        $paymentMethods = PaymentMethod::all();
        return view('payment.create', compact('sales', 'paymentMethods'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,completed,failed',
        ]);
        $payment = Payment::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($payment, 201);
        }
        return redirect()->route('payments.index');
    }
    public function show(Request $request, Payment $payment)
    {
        $payment->load(['sale.customer', 'paymentMethod']);
        if ($request->expectsJson()) {
            return response()->json($payment);
        }
        return view('payment.show', compact('payment'));
    }
    public function edit(Payment $payment)
    {
        $sales = Sale::with('customer')->get();
        $paymentMethods = PaymentMethod::all();
        return view('payment.edit', compact('payment', 'sales', 'paymentMethods'));
    }
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,completed,failed',
        ]);
        $payment->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($payment);
        }
        return redirect()->route('payments.index');
    }
    public function destroy(Request $request, Payment $payment)
    {
        $payment->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('payments.index');
    }
}

