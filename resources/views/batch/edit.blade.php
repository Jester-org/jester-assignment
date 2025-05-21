@extends('layouts.app')
@section('title', 'Batch Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Batch</h1>
    <form action="{{ route('batches.update', $batch) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $batch->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="batch_number">Batch Number</label>
            <input type="text" name="batch_number" id="batch_number" class="form-control" value="{{ $batch->batch_number }}" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $batch->quantity }}" min="1" required>
        </div>
        <div class="form-group">
            <label for="received_at">Received At</label>
            <input type="datetime-local" name="received_at" id="received_at" class="form-control" value="{{ $batch->received_at ? $batch->received_at->format('Y-m-d\TH:i') : '' }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

