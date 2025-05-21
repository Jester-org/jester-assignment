@extends('layouts.app')
@section('title', 'Leave Show')
@push('styles')
@endpush
@section('content')
    <h1>Leave Details</h1>
    <p><strong>User:</strong> {{ $leave->user->name ?? 'N/A' }}</p>
    <p><strong>Leave Type:</strong> {{ $leave->leaveType->name ?? 'N/A' }}</p>
    <p><strong>Start Date:</strong> {{ $leave->start_date }}</p>
    <p><strong>End Date:</strong> {{ $leave->end_date }}</p>
    <p><strong>Status:</strong> {{ ucfirst($leave->status) }}</p>
    <p><strong>Reason:</strong> {{ $leave->reason ?? 'N/A' }}</p>
    <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

