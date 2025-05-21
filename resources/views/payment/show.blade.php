@extends('layouts.app')
@section('title', 'Payment Show')
@push('styles')
@endpush
@section('content')
    <h1>Payment Details</h1>
    <p><strong>Sale (Customer):</strong> {{ $payment->sale->customer->name ?? 'N/A' }} (ID: {{ $payment->sale_id }})</p>
    <p><strong>Payment Method:</strong> {{ $payment->paymentMethod->name ?? 'N/A' }}</p>
    <p><strong>Amount:</strong> {{ $payment->amount }}</p>
    <p><strong>Payment Date:</strong> {{ $payment->payment_date }}</p>
    <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

