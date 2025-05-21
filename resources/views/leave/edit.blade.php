@extends('layouts.app')
@section('title', 'Leave Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Leave</h1>
    <form action="{{ route('leaves.update', $leave) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $leave->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="leave_type_id">Leave Type</label>
            <select name="leave_type_id" id="leave_type_id" class="form-control" required>
                @foreach ($leaveTypes as $leaveType)
                    <option value="{{ $leaveType->id }}" {{ $leave->leave_type_id == $leaveType->id ? 'selected' : '' }}>{{ $leaveType->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $leave->start_date }}" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $leave->end_date }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" {{ $leave->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $leave->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $leave->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="form-group">
            <label for="reason">Reason</label>
            <textarea name="reason" id="reason" class="form-control">{{ $leave->reason }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

