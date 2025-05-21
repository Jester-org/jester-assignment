@extends('layouts.app')
@section('title', 'Audit Log Show')
@push('styles')
@endpush
@section('content')
    <h1>Audit Log Details</h1>
    <p><strong>User:</strong> {{ $auditLog->user->name ?? 'N/A' }}</p>
    <p><strong>Action:</strong> {{ ucfirst($auditLog->action) }}</p>
    <p><strong>Description:</strong> {{ $auditLog->description ?? 'N/A' }}</p>
    <p><strong>Performed At:</strong> {{ $auditLog->performed_at }}</p>
    <a href="{{ route('audit-logs.edit', $auditLog) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('audit-logs.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

