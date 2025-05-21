@extends('layouts.app')
@section('title', 'Permission Show')
@push('styles')
@endpush
@section('content')
    <h1>Permission Details</h1>
    <p><strong>Name:</strong> {{ $permission->name }}</p>
    <p><strong>Description:</strong> {{ $permission->description ?? 'N/A' }}</p>
    <p><strong>Roles:</strong> {{ $permission->roles->pluck('name')->join(', ') ?: 'None' }}</p>
    <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

