@extends('layouts.app')
@section('title', 'Purchase Item Index')
@push('styles')
@endpush
@section('content')
    <h1>Purchase Items</h1>
    <a href="{{ route('purchase-items.create') }}" class="btn btn-primary">Create Purchase Item</a>
    <table class="table">
        <thead>
            <tr>
                <th>Purchase (Supplier)</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseItems as $purchaseItem)
                <tr>
                    <td>{{ $purchaseItem->purchase->supplier->name ?? 'N/A' }} (ID: {{ $purchaseItem->purchase_id }})</td>
                    <td>{{ $purchaseItem->product->name ?? 'N/A' }}</td>
                    <td>{{ $purchaseItem->quantity }}</td>
                    <td>{{ $purchaseItem->unit_price }}</td>
                    <td>{{ $purchaseItem->quantity * $purchaseItem->unit_price }}</td>
                    <td>
                        <a href="{{ route('purchase-items.show', $purchaseItem) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('purchase-items.edit', $purchaseItem) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('purchase-items.destroy', $purchaseItem) }}" method="POST" style="display:inline;">
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

