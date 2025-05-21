@extends('layouts.app')
@section('title', 'Attendance Index')
@push('styles')
@endpush
@section('content')
    <h1>Attendances</h1>
    <a href="{{ route('attendances.create') }}" class="btn btn-primary">Create Attendance</a>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->check_in }}</td>
                    <td>{{ $attendance->check_out ?? 'N/A' }}</td>
                    <td>{{ ucfirst($attendance->status) }}</td>
                    <td>
                        <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" style="display:inline;">
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

