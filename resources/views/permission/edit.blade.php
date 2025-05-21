@extends('layouts.app')
@section('title', 'Permission Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Permission</h1>
    <form action="{{ route('permissions.update', $permission) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $permission->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $permission->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="roles">Roles</label>
            <select name="roles[]" id="roles" class="form-control" multiple>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $permission->roles->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

