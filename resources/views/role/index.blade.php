@extends('layouts.app')
@section('title', 'Role Index')
@push('styles')
@endpush
@section('content')
    <h1>Roles</h1>
    <a href="{{ route('roles.create') }}" class="btn btn-primary">Create Role</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>
                        <a href="{{ route('roles.show', $role) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@push('scripts')
@endpush

