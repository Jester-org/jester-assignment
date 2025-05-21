@extends('layouts.app')
@section('title', 'Leave Index')
@push('styles')
@endpush
@section('content')
    <h1>Leaves</h1>
    <a href="{{ route('leaves.create') }}" class="btn btn-primary">Create Leave</a>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
                <tr>
                    <td>{{ $leave->user->name ?? 'N/A' }}</td>
                    <td>{{ $leave->leaveType->name ?? 'N/A' }}</td>
                    <td>{{ $leave->start_date }}</td>
                    <td>{{ $leave->end_date }}</td>
                    <td>{{ ucfirst($leave->status) }}</td>
                    <td>
                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('leaves.destroy', $leave) }}" method="POST" style="display:inline;">
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

