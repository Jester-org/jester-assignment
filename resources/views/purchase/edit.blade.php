@extends('layouts.app')

@section('title', 'Edit Purchase')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <h1>Edit Purchase</h1>
    <a href="{{ route('purchases.index') }}" class="btn btn-secondary mb-3">Back to Purchases</a>
    <form action="{{ route('purchases.update', $purchase) }}" method="POST" id="purchase-form">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required>
                <option value="">Select Supplier</option>
                @forelse ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @empty
                    <option value="">No suppliers available</option>
                @endforelse
            </select>
            @error('supplier_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Select User</option>
                @forelse ($users as $user)
                    <option value="{{ $user->id }}" {{ $purchase->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @empty
                    <option value="">No users available</option>
                @endforelse
            </select>
            @error('user_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" min="0" value="{{ $purchase->total_amount }}" readonly>
            @error('total_amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="purchase_date" class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ $purchase->purchase_date->format('Y-m-d') }}" required>
            @error('purchase_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" {{ $purchase->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $purchase->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $purchase->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <h3>Purchase Items</h3>
        <table class="table" id="purchase-items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="purchase-items">
                @forelse ($purchase->purchaseItems as $index => $item)
                    <tr class="purchase-item-row">
                        <td>
                            <select name="purchase_items[{{ $index }}][product_id]" class="form-control product-select" required>
                                <option value="">Select Product</option>
                                @forelse ($products as $product)
                                    <option value="{{ $product->id }}" data-unit-price="{{ $product->unit_price ?? 0 }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} (${{ number_format($product->unit_price ?? 0, 2) }})</option>
                                @empty
                                    <option value="">No products available</option>
                                @endforelse
                            </select>
                            @error("purchase_items.{$index}.product_id")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="purchase_items[{{ $index }}][quantity]" class="form-control quantity-input" min="1" value="{{ $item->quantity }}" required>
                            @error("purchase_items.{$index}.quantity")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="purchase_items[{{ $index }}][unit_price]" class="form-control unit-price" step="0.01" min="0" value="{{ $item->unit_price }}" readonly required>
                            @error("purchase_items.{$index}.unit_price")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control subtotal" step="0.01" value="{{ $item->subtotal }}" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-item">Remove</button>
                        </td>
                    </tr>
                @empty
                    <tr class="purchase-item-row">
                        <td>
                            <select name="purchase_items[0][product_id]" class="form-control product-select" required>
                                <option value="">Select Product</option>
                                @forelse ($products as $product)
                                    <option value="{{ $product->id }}" data-unit-price="{{ $product->unit_price ?? 0 }}">{{ $product->name }} (${{ number_format($product->unit_price ?? 0, 2) }})</option>
                                @empty
                                    <option value="">No products available</option>
                                @endforelse
                            </select>
                            @error('purchase_items.0.product_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="purchase_items[0][quantity]" class="form-control quantity-input" min="1" value="1" required>
                            @error('purchase_items.0.quantity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="purchase_items[0][unit_price]" class="form-control unit-price" step="0.01" min="0" readonly required>
                            @error('purchase_items.0.unit_price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control subtotal" step="0.01" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-item">Remove</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <button type="button" class="btn btn-primary" id="add-item">Add Item</button>
        <div class="mt-3">
            <button type="submit" class="btn btn-success" id="submit-purchase">Update Purchase</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script>
        console.log('Edit purchase script loaded');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) {
            console.error('CSRF token not found');
        }
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

        let itemIndex = {{ $purchase->purchaseItems->count() ?: 1 }};

        function updateTotalAmount() {
            const subtotals = document.querySelectorAll('.subtotal');
            let total = 0;
            subtotals.forEach(subtotal => {
                total += parseFloat(subtotal.value) || 0;
            });
            document.getElementById('total_amount').value = total.toFixed(2);
            console.log('Total Amount updated:', total.toFixed(2));
        }

        function toggleRowFields(row) {
            const quantityInput = row.querySelector('.quantity-input');
            const unitPriceInput = row.querySelector('.unit-price');
            const subtotalInput = row.querySelector('.subtotal');

            quantityInput.removeAttribute('readonly');
            unitPriceInput.setAttribute('readonly', 'readonly'); // Always read-only
            if (!quantityInput.value) {
                quantityInput.value = 1;
            }
            console.log('Row set to editable (unit_price read-only)');
        }

        document.getElementById('add-item').addEventListener('click', () => {
            console.log('Add item clicked');
            const tbody = document.getElementById('purchase-items');
            const newRow = document.createElement('tr');
            newRow.classList.add('purchase-item-row');
            newRow.innerHTML = `
                <td>
                    <select name="purchase_items[${itemIndex}][product_id]" class="form-control product-select" required>
                        <option value="">Select Product</option>
                        @forelse ($products as $product)
                            <option value="{{ $product->id }}" data-unit-price="{{ $product->unit_price ?? 0 }}">{{ $product->name }} (${{ number_format($product->unit_price ?? 0, 2) }})</option>
                        @empty
                            <option value="">No products available</option>
                        @endforelse
                    </select>
                </td>
                <td>
                    <input type="number" name="purchase_items[${itemIndex}][quantity]" class="form-control quantity-input" min="1" value="1" required>
                </td>
                <td>
                    <input type="number" name="purchase_items[${itemIndex}][unit_price]" class="form-control unit-price" step="0.01" min="0" readonly required>
                </td>
                <td>
                    <input type="number" class="form-control subtotal" step="0.01" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </td>
            `;
            tbody.appendChild(newRow);
            itemIndex++;
            updateTotalAmount();
        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-item')) {
                console.log('Remove item clicked');
                const rows = document.querySelectorAll('.purchase-item-row');
                const row = e.target.closest('tr');

                if (rows.length === 1) {
                    // Reset fields for single item
                    const productSelect = row.querySelector('.product-select');
                    const quantityInput = row.querySelector('.quantity-input');
                    const unitPriceInput = row.querySelector('.unit-price');
                    const subtotalInput = row.querySelector('.subtotal');

                    productSelect.value = '';
                    quantityInput.value = '1';
                    unitPriceInput.value = '';
                    subtotalInput.value = '';
                    toggleRowFields(row);
                    console.log('Single item reset');
                } else {
                    // Remove row for multiple items
                    row.remove();
                    console.log('Row removed');
                }

                updateTotalAmount();
            }
        });

        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('product-select') || 
                e.target.classList.contains('quantity-input')) {
                console.log('Input changed:', e.target.className, e.target.value);
                const row = e.target.closest('tr');
                const productSelect = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const unitPriceInput = row.querySelector('.unit-price');
                const subtotalInput = row.querySelector('.subtotal');

                if (e.target.classList.contains('product-select') && productSelect.value) {
                    const selectedOption = productSelect.selectedOptions[0];
                    const unitPrice = parseFloat(selectedOption.dataset.unitPrice) || 0;
                    console.log('Product selected - Unit Price:', unitPrice);
                    unitPriceInput.value = unitPrice.toFixed(2);
                    toggleRowFields(row);
                }

                // Calculate subtotal
                if (quantityInput.value && unitPriceInput.value) {
                    const subtotal = parseInt(quantityInput.value) * parseFloat(unitPriceInput.value);
                    subtotalInput.value = Math.max(0, subtotal).toFixed(2);
                    console.log('Subtotal calculated:', subtotalInput.value);
                }

                updateTotalAmount();
            }
        });

        // Initialize total amount on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateTotalAmount();
        });
    </script>
@endpush