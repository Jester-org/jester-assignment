@extends('layouts.app')
@section('title', 'Leave Type Show')
@push('styles')
@endpush
@section('content')
    <h1>Leave Type Details</h1>
    <p><strong>Name:</strong> {{ $leaveType->name }}</p>
    <p><strong>Description:</strong> {{ $leaveType->description ?? 'N/A' }}</p>
    <a href="{{ route('leave-types.edit', $leaveType) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('leave-types.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

