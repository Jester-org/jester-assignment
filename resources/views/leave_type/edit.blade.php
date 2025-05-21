@extends('layouts.app')
@section('title', 'Leave Type Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Leave Type</h1>
    <form action="{{ route('leave-types.update', $leaveType) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $leaveType->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $leaveType->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

