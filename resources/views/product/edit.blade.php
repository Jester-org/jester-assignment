@extends('layouts.app')
@section('title', 'Product Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Product</h1>
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="tax_rate_id">Tax Rate</label>
            <select name="tax_rate_id" id="tax_rate_id" class="form-control" required>
                @foreach ($taxRates as $taxRate)
                    <option value="{{ $taxRate->id }}" {{ $product->tax_rate_id == $taxRate->id ? 'selected' : '' }}>{{ $taxRate->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="barcode">Barcode</label>
            <input type="text" name="barcode" id="barcode" class="form-control" value="{{ $product->barcode }}" required>
        </div>
        <div class="form-group">
            <label for="unit_price">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" step="0.01" value="{{ $product->unit_price }}" required>
        </div>
        <div class="form-group">
            <label for="reorder_threshold">Reorder Threshold</label>
            <input type="number" name="reorder_threshold" id="reorder_threshold" class="form-control" value="{{ $product->reorder_threshold }}" required>
        </div>
        <div class="form-group">
            <label for="supplier_ids">Suppliers</label>
            <select name="supplier_ids[]" id="supplier_ids" class="form-control" multiple>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $product->suppliers->contains($supplier->id) ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

