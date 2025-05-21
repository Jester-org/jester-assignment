<?php
namespace App\Http\Controllers;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $paymentMethods = PaymentMethod::all();
        if ($request->expectsJson()) {
            return response()->json($paymentMethods);
        }
        return view('payment_method.index', compact('paymentMethods'));
    }
    public function create()
    {
        return view('payment_method.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $paymentMethod = PaymentMethod::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($paymentMethod, 201);
        }
        return redirect()->route('payment-methods.index');
    }
    public function show(Request $request, PaymentMethod $paymentMethod)
    {
        if ($request->expectsJson()) {
            return response()->json($paymentMethod);
        }
        return view('payment_method.show', compact('paymentMethod'));
    }
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment_method.edit', compact('paymentMethod'));
    }
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $paymentMethod->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($paymentMethod);
        }
        return redirect()->route('payment-methods.index');
    }
    public function destroy(Request $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('payment-methods.index');
    }
}

