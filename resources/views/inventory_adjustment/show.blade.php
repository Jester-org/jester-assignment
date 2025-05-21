@extends('layouts.app')
@section('title', 'Inventory Adjustment Show')
@push('styles')
@endpush
@section('content')
    <h1>Inventory Adjustment Details</h1>
    <p><strong>Product:</strong> {{ $inventoryAdjustment->inventory->product->name ?? 'N/A' }}</p>
    <p><strong>Adjustment Type:</strong> {{ ucfirst($inventoryAdjustment->adjustment_type) }}</p>
    <p><strong>Quantity:</strong> {{ $inventoryAdjustment->quantity }}</p>
    <p><strong>Reason:</strong> {{ $inventoryAdjustment->reason ?? 'N/A' }}</p>
    <p><strong>Adjustment Date:</strong> {{ $inventoryAdjustment->adjustment_date }}</p>
    <a href="{{ route('inventory-adjustments.edit', $inventoryAdjustment) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('inventory-adjustments.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

