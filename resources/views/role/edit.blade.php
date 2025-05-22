@extends('layouts.app')
@section('title', 'Role Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Role</h1>
    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $role->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

