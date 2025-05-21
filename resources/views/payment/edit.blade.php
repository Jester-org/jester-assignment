@extends('layouts.app')
@section('title', 'Payment Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Payment</h1>
    <form action="{{ route('payments.update', $payment) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="sale_id">Sale (Customer)</label>
            <select name="sale_id" id="sale_id" class="form-control" required>
                @foreach ($sales as $sale)
                    <option value="{{ $sale->id }}" {{ $payment->sale_id == $sale->id ? 'selected' : '' }}>{{ $sale->customer->name }} (ID: {{ $sale->id }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="payment_method_id">Payment Method</label>
            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                @foreach ($paymentMethods as $paymentMethod)
                    <option value="{{ $paymentMethod->id }}" {{ $payment->payment_method_id == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" value="{{ $payment->amount }}" required>
        </div>
        <div class="form-group">
            <label for="payment_date">Payment Date</label>
            <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ $payment->payment_date }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

