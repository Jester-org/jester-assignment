@extends('layouts.app')
@section('title', 'Tax Rate Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Tax Rate</h1>
    <form action="{{ route('tax-rates.update', $taxRate) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $taxRate->name }}" required>
        </div>
        <div class="form-group">
            <label for="rate">Rate (%)</label>
            <input type="number" name="rate" id="rate" class="form-control" step="0.01" min="0" max="100" value="{{ $taxRate->rate }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

