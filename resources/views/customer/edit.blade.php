@extends('layouts.app')

@section('title', 'Customer Edit')

@push('styles')
    
@endpush

@section('content')
    <h1>Edit Customer</h1>
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $customer->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $customer->email }}" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ $customer->phone }}">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" class="form-control">{{ $customer->address }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection

@push('scripts')
    
@endpush