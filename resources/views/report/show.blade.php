@extends('layouts.app')
@section('title', 'Report Show')
@push('styles')
@endpush
@section('content')
    <h1>Report Details</h1>
    <p><strong>Name:</strong> {{ $report->name }}</p>
    <p><strong>Type:</strong> {{ ucfirst($report->type) }}</p>
    <p><strong>Description:</strong> {{ $report->description ?? 'N/A' }}</p>
    <p><strong>Generated At:</strong> {{ $report->generated_at ?? 'N/A' }}</p>
    <a href="{{ route('reports.edit', $report) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

