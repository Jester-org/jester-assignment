@extends('layouts.app')
@section('title', 'Purchase Show')
@push('styles')
@endpush
@section('content')
    <h1>Purchase Details</h1>
    <p><strong>Supplier:</strong> {{ $purchase->supplier->name ?? 'N/A' }}</p>
    <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date }}</p>
    <p><strong>Total Amount:</strong> {{ $purchase->total_amount }}</p>
    <p><strong>Status:</strong> {{ ucfirst($purchase->status) }}</p>
    <p><strong>Items:</strong> {{ $purchase->purchaseItems->count() }}</p>
    @if($purchase->purchaseItems->count() > 0)
        <h3>Purchase Items</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->purchaseItems as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit_price }}</td>
                        <td>{{ $item->quantity * $item->unit_price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

