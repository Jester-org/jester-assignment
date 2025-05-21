@extends('layouts.app')
@section('title', 'Inventory Show')
@push('styles')
@endpush
@section('content')
    <h1>Inventory Details</h1>
    <p><strong>Product:</strong> {{ $inventory->product->name ?? 'N/A' }}</p>
    <p><strong>Quantity:</strong> {{ $inventory->quantity }}</p>
    <p><strong>Last Updated:</strong> {{ $inventory->last_updated ?? 'N/A' }}</p>
    <a href="{{ route('inventories.edit', $inventory) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('inventories.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

