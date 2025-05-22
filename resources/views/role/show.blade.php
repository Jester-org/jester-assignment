@extends('layouts.app')
@section('title', 'Role Show')
@push('styles')
@endpush
@section('content')
    <h1>Role Details</h1>
    <p><strong>Name:</strong> {{ $role->name }}</p>
    <p><strong>Description:</strong> {{ $role->description ?? 'N/A' }}</p>
    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

