@extends('layouts.app')
@section('title', 'Leave Type Index')
@push('styles')
@endpush
@section('content')
    <h1>Leave Types</h1>
    <a href="{{ route('leave-types.create') }}" class="btn btn-primary">Create Leave Type</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveTypes as $leaveType)
                <tr>
                    <td>{{ $leaveType->name }}</td>
                    <td>
                        <a href="{{ route('leave-types.show', $leaveType) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('leave-types.edit', $leaveType) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('leave-types.destroy', $leaveType) }}" method="POST" style="display:inline;">
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

