@extends('layouts.app')
@section('title', 'Payment Method Show')
@push('styles')
@endpush
@section('content')
    <h1>Payment Method Details</h1>
    <p><strong>Name:</strong> {{ $paymentMethod->name }}</p>
    <p><strong>Description:</strong> {{ $paymentMethod->description ?? 'N/A' }}</p>
    <a href="{{ route('payment-methods.edit', $paymentMethod) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

