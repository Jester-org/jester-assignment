@extends('layouts.app')
@section('title', 'Supplier Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Supplier</h1>
    <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $supplier->name }}" required>
        </div>
        <div class="form-group">
            <label for="contact_name">Contact Name</label>
            <input type="text" name="contact_name" id="contact_name" class="form-control" value="{{ $supplier->contact_name }}">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $supplier->email }}">
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ $supplier->phone }}">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" class="form-control">{{ $supplier->address }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

