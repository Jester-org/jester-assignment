@extends('layouts.app')
@section('title', 'Purchase Item Show')
@push('styles')
@endpush
@section('content')
    <h1>Purchase Item Details</h1>
    <p><strong>Purchase (Supplier):</strong> {{ $purchaseItem->purchase->supplier->name ?? 'N/A' }} (ID: {{ $purchaseItem->purchase_id }})</p>
    <p><strong>Product:</strong> {{ $purchaseItem->product->name ?? 'N/A' }}</p>
    <p><strong>Quantity:</strong> {{ $purchaseItem->quantity }}</p>
    <p><strong>Unit Price:</strong> {{ $purchaseItem->unit_price }}</p>
    <p><strong>Subtotal:</strong> {{ $purchaseItem->quantity * $purchaseItem->unit_price }}</p>
    <a href="{{ route('purchase-items.edit', $purchaseItem) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('purchase-items.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

