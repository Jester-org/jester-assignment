@extends('layouts.app')

@section('title', 'Sale Edit')

@push('styles')
    <style>
        .sale-item-row { margin-bottom: 10px; border: 1px solid #ddd; padding: 10px; position: relative; }
        .remove-item-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
            color: red;
            font-weight: bold;
            border: none;
            background: none;
            font-size: 18px;
        }
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
                    <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $sale->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" readonly required value="{{ $sale->total_amount }}">
        </div>

        <div class="form-group">
            <label for="sale_date">Sale Date</label>
            <input type="date" name="sale_date" id="sale_date" class="form-control"
                value="{{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d') }}" required>
        </div>

        <h3>Sale Items</h3>
        <div id="sale-items">
            @foreach ($sale->saleItems as $index => $saleItem)
                <div class="sale-item-row" data-index="{{ $index }}">
                    <button type="button" class="remove-item-btn" onclick="removeSaleItem(this)">×</button>
                    <div class="form-group">
                        <label>Product</label>
                        <select name="sale_items[{{ $index }}][product_id]" class="form-control product-select" onchange="onProductSelect(this)">
                            <option value="">-- Select Product --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    data-stock="{{ $product->inventory ? $product->inventory->quantity : 'N/A' }}"
                                    data-price="{{ $product->unit_price ?? 0 }}"
                                    {{ $saleItem->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->inventory ? $product->inventory->quantity : 'N/A' }} in stock)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="sale_items[{{ $index }}][quantity]" class="form-control quantity-input" min="1" value="{{ $saleItem->quantity }}" oninput="calculateTotalAmount()">
                    </div>
                    <div class="form-group">
                        <label>Unit Price</label>
                        <input type="number" name="sale_items[{{ $index }}][unit_price]" class="form-control unit-price-input" step="0.01" value="{{ $saleItem->unit_price }}" readonly>
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
        const products = @json($products);
        let itemIndex = {{ $sale->saleItems->count() }};

        function addSaleItem() {
            const container = document.getElementById('sale-items');
            const row = document.createElement('div');
            row.className = 'sale-item-row';
            row.setAttribute('data-index', itemIndex);

            let options = `<option value="">-- Select Product --</option>`;
            products.forEach(product => {
                // Access quantity from inventory relationship
                const stock = product.inventory && product.inventory.quantity !== undefined ? product.inventory.quantity : 'N/A';
                options += `<option value="${product.id}" data-stock="${stock}" data-price="${product.unit_price || 0}">
                    ${product.name} (${stock} in stock)
                </option>`;
            });

            row.innerHTML = `
                <button type="button" class="remove-item-btn" onclick="removeSaleItem(this)">×</button>
                <div class="form-group">
                    <label>Product</label>
                    <select name="sale_items[${itemIndex}][product_id]" class="form-control product-select" onchange="onProductSelect(this)">
                        ${options}
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="sale_items[${itemIndex}][quantity]" class="form-control quantity-input" min="1" value="1" oninput="calculateTotalAmount()">
                </div>
                <div class="form-group">
                    <label>Unit Price</label>
                    <input type="number" name="sale_items[${itemIndex}][unit_price]" class="form-control unit-price-input" step="0.01" readonly>
                </div>
            `;

            container.appendChild(row);
            itemIndex++;
        }

        function removeSaleItem(button) {
            const row = button.closest('.sale-item-row');
            if (row) row.remove();
            calculateTotalAmount();
        }

        function onProductSelect(select) {
            const selected = select.options[select.selectedIndex];
            const price = selected.getAttribute('data-price') || 0;

            const row = select.closest('.sale-item-row');
            row.querySelector('.unit-price-input').value = price;
            row.querySelector('.quantity-input').value = 1;

            calculateTotalAmount();
        }

        function calculateTotalAmount() {
            let total = 0;
            document.querySelectorAll('.sale-item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.quantity-input')?.value) || 0;
                const price = parseFloat(row.querySelector('.unit-price-input')?.value) || 0;
                total += qty * price;
            });
            document.getElementById('total_amount').value = total.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', () => {
            calculateTotalAmount(); // on page load
        });
    </script>
@endpush