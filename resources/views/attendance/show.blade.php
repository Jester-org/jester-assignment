@extends('layouts.app')
@section('title', 'Attendance Show')
@push('styles')
@endpush
@section('content')
    <h1>Attendance Details</h1>
    <p><strong>User:</strong> {{ $attendance->user->name ?? 'N/A' }}</p>
    <p><strong>Check In:</strong> {{ $attendance->check_in }}</p>
    <p><strong>Check Out:</strong> {{ $attendance->check_out ?? 'N/A' }}</p>
    <p><strong>Status:</strong> {{ ucfirst($attendance->status) }}</p>
    <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

