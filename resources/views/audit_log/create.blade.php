@extends('layouts.app')
@section('title', 'Audit Log Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Audit Log</h1>
    <form action="{{ route('audit-logs.store') }}" method="POST">
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
            <label for="action">Action</label>
            <input type="text" name="action" id="action" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="performed_at">Performed At</label>
            <input type="datetime-local" name="performed_at" id="performed_at" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

