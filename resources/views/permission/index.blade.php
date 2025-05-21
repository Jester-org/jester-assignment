@extends('layouts.app')
@section('title', 'Permission Index')
@push('styles')
@endpush
@section('content')
    <h1>Permissions</h1>
    <a href="{{ route('permissions.create') }}" class="btn btn-primary">Create Permission</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Roles</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->description ?? 'N/A' }}</td>
                    <td>{{ $permission->roles->pluck('name')->join(', ') ?: 'None' }}</td>
                    <td>
                        <a href="{{ route('permissions.show', $permission) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display:inline;">
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

