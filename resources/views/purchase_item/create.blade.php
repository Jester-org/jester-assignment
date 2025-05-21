@extends('layouts.app')
@section('title', 'Purchase Item Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Purchase Item</h1>
    <form action="{{ route('purchase-items.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="purchase_id">Purchase (Supplier)</label>
            <select name="purchase_id" id="purchase_id" class="form-control" required>
                @foreach ($purchases as $purchase)
                    <option value="{{ $purchase->id }}">{{ $purchase->supplier->name }} (ID: {{ $purchase->id }})</option>
                @endforeach
            </select>
        </div>
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
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label for="unit_price">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" step="0.01" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

