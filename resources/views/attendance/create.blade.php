@extends('layouts.app')
@section('title', 'Attendance Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Attendance</h1>
    <form action="{{ route('attendances.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="check_in">Check In</label>
            <input type="datetime-local" name="check_in" id="check_in" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="check_out">Check Out</label>
            <input type="datetime-local" name="check_out" id="check_out" class="form-control">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
                <option value="late">Late</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

