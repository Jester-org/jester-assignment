@extends('layouts.app')
@section('title', 'Inventory Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Inventory</h1>
    <form action="{{ route('inventories.store') }}" method="POST">
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
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="0" required>
        </div>
        <div class="form-group">
            <label for="last_updated">Last Updated</label>
            <input type="datetime-local" name="last_updated" id="last_updated" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

