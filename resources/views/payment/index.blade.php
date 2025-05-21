@extends('layouts.app')
@section('title', 'Payment Index')
@push('styles')
@endpush
@section('content')
    <h1>Payments</h1>
    <a href="{{ route('payments.create') }}" class="btn btn-primary">Create Payment</a>
    <table class="table">
        <thead>
            <tr>
                <th>Sale (Customer)</th>
                <th>Payment Method</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->sale->customer->name ?? 'N/A' }} (ID: {{ $payment->sale_id }})</td>
                    <td>{{ $payment->paymentMethod->name ?? 'N/A' }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ ucfirst($payment->status) }}</td>
                    <td>
                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display:inline;">
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

