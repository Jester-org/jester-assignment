@extends('layouts.app')
@section('title', 'Attendance Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Attendance</h1>
    <form action="{{ route('attendances.update', $attendance) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $attendance->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="check_in">Check In</label>
            <input type="datetime-local" name="check_in" id="check_in" class="form-control" 
                   value="{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('Y-m-d\TH:i') : '' }}" required>
        </div>
        <div class="form-group">
            <label for="check_out">Check Out</label>
            <input type="datetime-local" name="check_out" id="check_out" class="form-control" 
                   value="{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('Y-m-d\TH:i') : '' }}">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Present</option>
                <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Late</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush
