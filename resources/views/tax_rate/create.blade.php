@extends('layouts.app')
@section('title', 'Tax Rate Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Tax Rate</h1>
    <form action="{{ route('tax-rates.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="rate">Rate (%)</label>
            <input type="number" name="rate" id="rate" class="form-control" step="0.01" min="0" max="100" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

