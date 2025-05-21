@extends('layouts.app')
@section('title', 'Expiry Date Show')
@push('styles')
@endpush
@section('content')
    <h1>Expiry Date Details</h1>
    <p><strong>Batch:</strong> {{ $expiryDate->batch->batch_number ?? 'N/A' }}</p>
    <p><strong>Expiry Date:</strong> {{ $expiryDate->expiry_date }}</p>
    <p><strong>Notes:</strong> {{ $expiryDate->notes ?? 'N/A' }}</p>
    <a href="{{ route('expiry-dates.edit', $expiryDate) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('expiry-dates.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

