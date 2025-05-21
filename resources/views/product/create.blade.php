@extends('layouts.app')
@section('title', 'Product Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Product</h1>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="tax_rate_id">Tax Rate</label>
            <select name="tax_rate_id" id="tax_rate_id" class="form-control" required>
                @foreach ($taxRates as $taxRate)
                    <option value="{{ $taxRate->id }}">{{ $taxRate->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="barcode">Barcode</label>
            <input type="text" name="barcode" id="barcode" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="unit_price">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="reorder_threshold">Reorder Threshold</label>
            <input type="number" name="reorder_threshold" id="reorder_threshold" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="supplier_ids">Suppliers</label>
            <select name="supplier_ids[]" id="supplier_ids" class="form-control" multiple>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

