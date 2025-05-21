@extends('layouts.app')
@section('title', 'Purchase Item Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Purchase Item</h1>
    <form action="{{ route('purchase-items.update', $purchaseItem) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="purchase_id">Purchase (Supplier)</label>
            <select name="purchase_id" id="purchase_id" class="form-control" required>
                @foreach ($purchases as $purchase)
                    <option value="{{ $purchase->id }}" {{ $purchaseItem->purchase_id == $purchase->id ? 'selected' : '' }}>{{ $purchase->supplier->name }} (ID: {{ $purchase->id }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $purchaseItem->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="{{ $purchaseItem->quantity }}" required>
        </div>
        <div class="form-group">
            <label for="unit_price">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" step="0.01" min="0" value="{{ $purchaseItem->unit_price }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

