@extends('layouts.app')

@section('title', 'Sale Item Create')

@push('styles')
    
@endpush

@section('content')
    <h1>Create Sale Item</h1>
    <form action="{{ route('sale-items.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="sale_id">Sale</label>
            <select name="sale_id" id="sale_id" class="form-control" required>
                @foreach ($sales as $sale)
                    <option value="{{ $sale->id }}">Sale #{{ $sale->id }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name ?? 'Product #'.$product->id }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label for="unit_price">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="subtotal">Subtotal</label>
            <input type="number" name="subtotal" id="subtotal" class="form-control" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection

@push('scripts')
    
@endpush