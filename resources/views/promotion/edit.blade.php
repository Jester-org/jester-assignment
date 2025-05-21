@extends('layouts.app')
@section('title', 'Promotion Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Promotion</h1>
    <form action="{{ route('promotions.update', $promotion) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $promotion->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="discount_percentage">Discount Percentage (%)</label>
            <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" step="0.01" min="0" max="100" value="{{ $promotion->discount_percentage }}" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $promotion->start_date }}" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $promotion->end_date }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

