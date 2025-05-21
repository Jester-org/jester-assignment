@extends('layouts.app')
@section('title', 'Transaction Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Transaction</h1>
    <form action="{{ route('transactions.update', $transaction) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="sale_id">Sale (Customer)</label>
            <select name="sale_id" id="sale_id" class="form-control" required>
                @foreach ($sales as $sale)
                    <option value="{{ $sale->id }}" {{ $transaction->sale_id == $sale->id ? 'selected' : '' }}>{{ $sale->customer->name }} (ID: {{ $sale->id }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" value="{{ $transaction->amount }}" required>
        </div>
        <div class="form-group">
            <label for="transaction_date">Transaction Date</label>
            <input type="date" name="transaction_date" id="transaction_date" class="form-control" value="{{ $transaction->transaction_date }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

