@extends('layouts.app')

@section('title', 'Sale Item Show')

@push('styles')
    
@endpush

@section('content')
    <h1>Sale Item Details</h1>
    <p><strong>Sale:</strong> Sale #{{ $saleItem->sale->id }}</p>
    <p><strong>Product:</strong> {{ $saleItem->product->name ?? 'N/A' }}</p>
    <p><strong>Quantity:</strong> {{ $saleItem->quantity }}</p>
    <p><strong>Unit Price:</strong> {{ $saleItem->unit_price }}</p>
    <p><strong>Subtotal:</strong> {{ $saleItem->subtotal }}</p>
    <a href="{{ route('sale-items.edit', $saleItem) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('sale-items.index') }}" class="btn btn-secondary">Back</a>
@endsection

@push('scripts')
    
@endpush