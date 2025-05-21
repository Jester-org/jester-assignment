@extends('layouts.app')
@section('title', 'Payment Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Payment</h1>
    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="sale_id">Sale (Customer)</label>
            <select name="sale_id" id="sale_id" class="form-control" required>
                @foreach ($sales as $sale)
                    <option value="{{ $sale->id }}">{{ $sale->customer->name }} (ID: {{ $sale->id }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="payment_method_id">Payment Method</label>
            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                @foreach ($paymentMethods as $paymentMethod)
                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" required>
        </div>
        <div class="form-group">
            <label for="payment_date">Payment Date</label>
            <input type="date" name="payment_date" id="payment_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

