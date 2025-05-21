@extends('layouts.app')
@section('title', 'Payment Method Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Payment Method</h1>
    <form action="{{ route('payment-methods.update', $paymentMethod) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $paymentMethod->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $paymentMethod->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

