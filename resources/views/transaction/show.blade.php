@extends('layouts.app')
@section('title', 'Transaction Show')
@push('styles')
@endpush
@section('content')
    <h1>Transaction Details</h1>
    <p><strong>Sale (Customer):</strong> {{ $transaction->sale->customer->name ?? 'N/A' }} (ID: {{ $transaction->sale_id }})</p>
    <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
    <p><strong>Transaction Date:</strong> {{ $transaction->transaction_date }}</p>
    <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

