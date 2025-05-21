@extends('layouts.app')

@section('title', 'Customer Show')

@push('styles')
    
@endpush

@section('content')
    <h1>Customer Details</h1>
    <p><strong>Name:</strong> {{ $customer->name }}</p>
    <p><strong>Email:</strong> {{ $customer->email }}</p>
    <p><strong>Phone:</strong> {{ $customer->phone }}</p>
    <p><strong>Address:</strong> {{ $customer->address }}</p>
    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
@endsection

@push('scripts')
    
@endpush