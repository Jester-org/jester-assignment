@extends('layouts.app')

@section('title', 'User Details')

@push('styles')
    <style>
        .card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; }
        .btn { padding: 8px 15px; text-decoration: none; }
        .btn-primary { background-color: #007bff; color: white; }
    </style>
@endpush

@section('content')
    <div class="container">
        <h1>User Details</h1>
        <div class="card">
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role }}</p>
            <p><strong>Admin:</strong> {{ $user->is_admin ? 'Yes' : 'No' }}</p>
            <p><strong>Created At:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
            <p><strong>Updated At:</strong> {{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
        </div>
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection

@push('scripts')
    <script>
        // Add any custom JavaScript for the show page if needed
    </script>
@endpush