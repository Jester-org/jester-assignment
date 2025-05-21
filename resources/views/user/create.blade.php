@extends('layouts.app')

@section('title', 'Create User')

@push('styles')
    <style>
        .form-group { margin-bottom: 15px; }
        .form-control { width: 100%; padding: 8px; }
        .btn { padding: 8px 15px; text-decoration: none; }
        .btn-primary { background-color: #007bff; color: white; }
        .alert { padding: 10px; margin-bottom: 15px; }
    </style>
@endpush

@section('content')
    <div class="container">
        <h1>Create User</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="is_admin">
                    <input type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                    Is Admin
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Add any custom JavaScript for the create page if needed
    </script>
@endpush