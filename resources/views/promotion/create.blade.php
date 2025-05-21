@extends('layouts.app')
@section('title', 'Promotion Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Promotion</h1>
    <form action="{{ route('promotions.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="discount_percentage">Discount Percentage (%)</label>
            <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" step="0.01" min="0" max="100" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

