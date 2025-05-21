@extends('layouts.app')
@section('title', 'Inventory Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Inventory</h1>
    <form action="{{ route('inventories.update', $inventory) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $inventory->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="0" value="{{ $inventory->quantity }}" required>
        </div>
        <div class="form-group">
            <label for="last_updated">Last Updated</label>
            <input type="datetime-local" name="last_updated" id="last_updated" class="form-control" value="{{ $inventory->last_updated ? $inventory->last_updated->format('Y-m-d\TH:i') : '' }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

