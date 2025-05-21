@extends('layouts.app')
@section('title', 'Batch Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Batch</h1>
    <form action="{{ route('batches.store') }}" method="POST">
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
            <label for="batch_number">Batch Number</label>
            <input type="text" name="batch_number" id="batch_number" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label for="received_at">Received At</label>
            <input type="datetime-local" name="received_at" id="received_at" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

