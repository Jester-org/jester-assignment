@extends('layouts.app')
@section('title', 'Audit Log Index')
@push('styles')
@endpush
@section('content')
    <h1>Audit Logs</h1>
    <a href="{{ route('audit-logs.create') }}" class="btn btn-primary">Create Audit Log</a>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Performed At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($auditLogs as $auditLog)
                <tr>
                    <td>{{ $auditLog->user->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($auditLog->action) }}</td>
                    <td>{{ $auditLog->performed_at }}</td>
                    <td>
                        <a href="{{ route('audit-logs.show', $auditLog) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('audit-logs.edit', $auditLog) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('audit-logs.destroy', $auditLog) }}" method="POST" style="display:inline;">
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

