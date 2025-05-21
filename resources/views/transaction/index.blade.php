@extends('layouts.app')
@section('title', 'Transaction Index')
@push('styles')
@endpush
@section('content')
    <h1>Transactions</h1>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">Create Transaction</a>
    <table class="table">
        <thead>
            <tr>
                <th>Sale (Customer)</th>
                <th>Amount</th>
                <th>Transaction Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->sale->customer->name ?? 'N/A' }} (ID: {{ $transaction->sale_id }})</td>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ $transaction->transaction_date }}</td>
                    <td>{{ ucfirst($transaction->status) }}</td>
                    <td>
                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@push('scripts')
@endpush

