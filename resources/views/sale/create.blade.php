@extends('layouts.app')

@section('title', 'Sale Create')

@push('styles')
    <style>
        .sale-item-row { margin-bottom: 10px; }
    </style>
@endpush

@section('content')
    <h1>Create Sale</h1>
    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="sale_date">Sale Date</label>
            <input type="date" name="sale_date" id="sale_date" class="form-control" required>
        </div>
        <h3>Sale Items</h3>
        <div id="sale-items">
            <div class="sale-item-row">
                <div class="form-group">
                    <label>Product</label>
                    <select name="sale_items[0][product_id]" class="form-control" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name ?? 'Product #'. $product->id }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="sale_items[0][quantity]" class="form-control" min="1" required>
                </div>
                <div class="form-group">
                    <label>Unit Price</label>
                    <input type="number" name="sale_items[0][unit_price]" class="form-control" step="0.01" required>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="addSaleItem()">Add Item</button>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection

@push('scripts')
    <script>
        let itemIndex = 1;
        function addSaleItem() {
            const container = document.getElementById('sale-items');
            const row = document.createElement('div');
            row.className = 'sale-item-row';
            row.innerHTML = `
                <div class="form-group">
                    <label>Product</label>
                    <select name="sale_items[${itemIndex}][product_id]" class="form-control" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name ?? 'Product #'. $product->id }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="sale_items[${itemIndex}][quantity]" class="form-control" min="1" required>
                </div>
                <div class="form-group">
                    <label>Unit Price</label>
                    <input type="number" name="sale_items[${itemIndex}][unit_price]" class="form-control" step="0.01" required>
                </div>
            `;
            container.appendChild(row);
            itemIndex++;
        }
    </script>
@endpush
