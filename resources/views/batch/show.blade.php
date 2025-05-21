@extends('layouts.app')
@section('title', 'Batch Show')
@push('styles')
@endpush
@section('content')
    <h1>Batch Details</h1>
    <p><strong>Product:</strong> {{ $batch->product->name ?? 'N/A' }}</p>
    <p><strong>Batch Number:</strong> {{ $batch->batch_number }}</p>
    <p><strong>Quantity:</strong> {{ $batch->quantity }}</p>
    <p><strong>Received At:</strong> {{ $batch->received_at }}</p>
    <a href="{{ route('batches.edit', $batch) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('batches.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

