@extends('layouts.app')

@section('title', 'Sale Edit')

@push('styles')
    <style>
        .sale-item-row { margin-bottom: 10px; }
    </style>
@endpush

@section('content')
    <h1>Edit Sale</h1>
    <form action="{{ route('sales.update', $sale) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $sale->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" value="{{ $sale->total_amount }}" required>
        </div>
        <div class="form-group">
            <label for="sale_date">Sale Date</label>
            <input type="date" name="sale_date" id="sale_date" class="form-control" value="{{ $sale->sale_date }}" required>
        </div>
        <h3>Sale Items</h3>
        <div id="sale-items">
            @foreach ($sale->saleItems as $index => $saleItem)
                <div class="sale-item-row">
                    <div class="form-group">
                        <label>Product</label>
                        <select name="sale_items[{$index}][product_id]" class="form-control" required>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ $saleItem->product_id == $product->id ? 'selected' : '' }}>{{ $product->name ?? 'Product #'. $product->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="sale_items[{$index}][quantity]" class="form-control" min="1" value="{{ $saleItem->quantity }}" required>
                    </div>
                    <div class="form-group">
                        <label>Unit Price</label>
                        <input type="number" name="sale_items[{$index}][unit_price]" class="form-control" step="0.01" value="{{ $saleItem->unit_price }}" required>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-secondary" onclick="addSaleItem()">Add Item</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection

@push('scripts')
    <script>
        let itemIndex = {{ $sale->saleItems->count() }};
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
