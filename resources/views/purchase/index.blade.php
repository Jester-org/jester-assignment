@extends('layouts.app')
@section('title', 'Purchase Index')
@push('styles')
@endpush
@section('content')
    <h1>Purchases</h1>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary">Create Purchase</a>
    <table class="table">
        <thead>
            <tr>
                <th>Supplier</th>
                <th>Purchase Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Items</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $purchase->purchase_date }}</td>
                    <td>{{ $purchase->total_amount }}</td>
                    <td>{{ ucfirst($purchase->status) }}</td>
                    <td>{{ $purchase->purchaseItems->count() }}</td>
                    <td>
                        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" style="display:inline;">
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

