@extends('layouts.app')
@section('title', 'Audit Log Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Audit Log</h1>
    <form action="{{ route('audit-logs.update', $auditLog) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $auditLog->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="action">Action</label>
            <input type="text" name="action" id="action" class="form-control" value="{{ $auditLog->action }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $auditLog->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="performed_at">Performed At</label>
            <input type="datetime-local" name="performed_at" id="performed_at" class="form-control" value="{{ $auditLog->performed_at ? $auditLog->performed_at->format('Y-m-d\TH:i') : '' }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

