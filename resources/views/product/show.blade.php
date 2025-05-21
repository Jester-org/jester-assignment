@extends('layouts.app')
@section('title', 'Product Show')
@push('styles')
@endpush
@section('content')
    <h1>Product Details</h1>
    <p><strong>Name:</strong> {{ $product->name }}</p>
    <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
    <p><strong>Tax Rate:</strong> {{ $product->taxRate->name ?? 'N/A' }}</p>
    <p><strong>Description:</strong> {{ $product->description ?? 'N/A' }}</p>
    <p><strong>Barcode:</strong> {{ $product->barcode }}</p>
    <p><strong>Unit Price:</strong> ${{ number_format($product->unit_price, 2) }}</p>
    <p><strong>Reorder Threshold:</strong> {{ $product->reorder_threshold }}</p>
    <p><strong>Stock Status:</strong>
        @if ($product->low_stock)
            <span class="text-danger">Low Stock ({{ $product->inventory->quantity ?? 0 }})</span>
        @else
            {{ $product->inventory->quantity ?? 0 }} in stock
        @endif
    </p>
    <p><strong>Suppliers:</strong> {{ $product->suppliers->pluck('name')->implode(', ') ?: 'None' }}</p>
    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

