@extends('layouts.app')
@section('title', 'Payment Method Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Payment Method</h1>
    <form action="{{ route('payment-methods.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

