@extends('layouts.app')
@section('title', 'Promotion Show')
@push('styles')
@endpush
@section('content')
    <h1>Promotion Details</h1>
    <p><strong>Product:</strong> {{ $promotion->product->name ?? 'N/A' }}</p>
    <p><strong>Discount Percentage (%):</strong> {{ $promotion->discount_percentage }}</p>
    <p><strong>Start Date:</strong> {{ $promotion->start_date }}</p>
    <p><strong>End Date:</strong> {{ $promotion->end_date }}</p>
    <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

