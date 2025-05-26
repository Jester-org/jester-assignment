@extends('layouts.app')
@section('title', 'Product Show')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush('content')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Product Details</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>Name:</strong> {{ $product->name }}</p>
        <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
        <p><strong>Tax Rate:</strong>: {{ $product->taxRate->display_name ?? 'N/A' }}</p>
        <p><strong>Description:</strong> {{ $product->description ?? 'N/A' }}</p>
        <p><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
        <p><strong>Base Price:</strong> ${{ number_format($product->base_price, 2) }}</p>
        <p><strong>VAT:</strong> ${{ number_format($product->vat, 2) }}</p>
        <p><strong>Unit Price:</strong> ${{ number_format($product->unit_price, 2) }}</p>
        <p><strong>Promotions:</strong> {{ $product->promotion_details }}</p>
        <p><strong>Reorder Threshold:</strong> {{ $product->reorder_threshold }}</p>
        <p><strong>Stock Status:</strong>
            @if ($product->stock_quantity > 0)
                @if ($product->low_stock)
                    <span class="text-red-500">Low Stock ({{ $product->stock_quantity }})</span>
                @else
                    <span class="text-green-600">{{ $product->stock_quantity }} in stock</span>
                @endif
            @else
                <span class="text-yellow-500">No Inventory</span>
            @endif
        </p>
        <p><strong>Suppliers:</strong> {{ $product->suppliers->pluck('name')->implode(', ') ?: 'None' }}</p>
        <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-yellow-500 text-white rounded inline-block">Edit</a>
        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded inline-block">Back</a>
    </div>
@endsection
@push('scripts')
@endpush