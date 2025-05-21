@extends('layouts.app')
@section('title', 'Supplier Show')
@push('styles')
@endpush
@section('content')
    <h1>Supplier Details</h1>
    <p><strong>Name:</strong> {{ $supplier->name }}</p>
    <p><strong>Contact Name:</strong> {{ $supplier->contact_name ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $supplier->email ?? 'N/A' }}</p>
    <p><strong>Phone:</strong> {{ $supplier->phone ?? 'N/A' }}</p>
    <p><strong>Address:</strong> {{ $supplier->address ?? 'N/A' }}</p>
    <p><strong>Purchases:</strong> {{ $supplier->purchases->count() }}</p>
    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

