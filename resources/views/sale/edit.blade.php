@extends('layouts.app')

@section('title', 'Edit Sale')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <h1>Edit Sale</h1>
    <a href="{{ route('sales.index') }}" class="btn btn-secondary mb-3">Back to Sales</a>
    <form action="{{ route('sales.update', $sale->id) }}" method="POST" id="sale-form">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="customer_id" class="form-label">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                @forelse ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                @empty
                    <option value="">No customers available</option>
                @endforelse
            </select>
            @error('customer_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Select User</option>
                @forelse ($users as $user)
                    <option value="{{ $user->id }}" {{ $sale->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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
            <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" min="0" readonly>
            @error('total_amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="sale_date" class="form-label">Sale Date</label>
            <input type="date" name="sale_date" id="sale_date" class="form-control" value="{{ $sale->sale_date }}" required>
            @error('sale_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <h3>Sale Items</h3>
        <table class="table" id="sale-items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Discount</th>
                    <th>Discount Type</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="sale-items">
                @forelse ($sale->saleItems as $index => $item)
                    <tr class="sale-item-row">
                        <td>
                            <select name="sale_items[{{ $index }}][product_id]" class="form-control product-select" required>
                                <option value="">Select Product</option>
                                @forelse ($products as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ optional($product->inventory)->quantity ?? 0 }}" data-unit-price="{{ $product->unit_price ?? 0 }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} (Stock: {{ optional($product->inventory)->quantity ?? 0 }})</option>
                                @empty
                                    <option value="">No products available</option>
                                @endforelse
                            </select>
                            <div class="text-danger stock-error"></div>
                            @error("sale_items.$index.product_id")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="sale_items[{{ $index }}][quantity]" class="form-control quantity-input" min="1" value="{{ $item->quantity }}" required>
                            @error("sale_items.$index.quantity")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="sale_items[{{ $index }}][unit_price]" class="form-control unit-price" step="0.01" min="0" value="{{ $item->unit_price }}" readonly required>
                            @error("sale_items.$index.unit_price")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="sale_items[{{ $index }}][discount]" class="form-control discount-input" step="0.01" min="0" value="0">
                            @error("sale_items.$index.discount")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <select name="sale_items[{{ $index }}][discount_type]" class="form-control discount-type">
                                <option value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control subtotal" step="0.01" value="{{ $item->subtotal }}" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-item">Remove</button>
                        </td>
                    </tr>
                @empty
                    <tr class="sale-item-row">
                        <td>
                            <select name="sale_items[0][product_id]" class="form-control product-select" required>
                                <option value="">Select Product</option>
                                @forelse ($products as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ optional($product->inventory)->quantity ?? 0 }}" data-unit-price="{{ $product->unit_price ?? 0 }}">{{ $product->name }} (Stock: {{ optional($product->inventory)->quantity ?? 0 }})</option>
                                @empty
                                    <option value="">No products available</option>
                                @endforelse
                            </select>
                            <div class="text-danger stock-error"></div>
                            @error('sale_items.0.product_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="sale_items[0][quantity]" class="form-control quantity-input" min="1" value="1" required>
                            @error('sale_items.0.quantity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="sale_items[0][unit_price]" class="form-control unit-price" step="0.01" min="0" readonly required>
                            @error('sale_items.0.unit_price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="sale_items[0][discount]" class="form-control discount-input" step="0.01" min="0" value="0">
                            @error('sale_items.0.discount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <select name="sale_items[0][discount_type]" class="form-control discount-type">
                                <option value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            </select>
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
            <button type="submit" class="btn btn-success" id="submit-sale" disabled>Update Sale</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script>
        console.log('Edit sale script loaded');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) {
            console.error('CSRF token not found');
        }
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

        let itemIndex = {{ $sale->saleItems->count() }};

        function updateTotalAmount() {
            const subtotals = document.querySelectorAll('.subtotal');
            let total = 0;
            subtotals.forEach(subtotal => {
                total += parseFloat(subtotal.value) || 0;
            });
            document.getElementById('total_amount').value = total.toFixed(2);
            console.log('Total Amount updated:', total.toFixed(2));
        }

        function toggleRowFields(row, isOutOfStock) {
            const quantityInput = row.querySelector('.quantity-input');
            const unitPriceInput = row.querySelector('.unit-price');
            const discountInput = row.querySelector('.discount-input');
            const discountType = row.querySelector('.discount-type');
            const subtotalInput = row.querySelector('.subtotal');

            if (isOutOfStock) {
                quantityInput.setAttribute('readonly', 'readonly');
                unitPriceInput.setAttribute('readonly', 'readonly');
                discountInput.setAttribute('readonly', 'readonly');
                discountType.setAttribute('disabled', 'disabled');
                quantityInput.value = '';
                discountInput.value = '0';
                subtotalInput.value = '';
                console.log('Row set to read-only: Out of stock');
            } else {
                quantityInput.removeAttribute('readonly');
                unitPriceInput.setAttribute('readonly', 'readonly'); // Always read-only
                discountInput.removeAttribute('readonly');
                discountType.removeAttribute('disabled');
                if (!quantityInput.value) {
                    quantityInput.value = 1;
                }
                console.log('Row set to editable (unit_price read-only)');
            }
        }

        function validateForm() {
            const rows = document.querySelectorAll('.sale-item-row');
            let isValid = true;
            rows.forEach(row => {
                const productSelect = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const stockError = row.querySelector('.stock-error');

                if (productSelect.value) {
                    const stock = parseInt(productSelect.selectedOptions[0].dataset.stock) || 0;
                    const quantity = parseInt(quantityInput.value) || 0;

                    if (stock <= 0) {
                        stockError.textContent = 'The product is out of stock.';
                        stockError.style.display = 'block';
                        isValid = false;
                    } else if (quantity > stock) {
                        stockError.textContent = `Insufficient stock. Available: ${stock}.`;
                        stockError.style.display = 'block';
                        isValid = false;
                    } else {
                        stockError.textContent = '';
                        stockError.style.display = 'none';
                    }
                }
            });

            const submitButton = document.getElementById('submit-sale');
            submitButton.disabled = !isValid;
            console.log('Form validation:', isValid ? 'Valid' : 'Invalid');
            return isValid;
        }

        document.getElementById('add-item').addEventListener('click', () => {
            console.log('Add item clicked');
            const tbody = document.getElementById('sale-items');
            const newRow = document.createElement('tr');
            newRow.classList.add('sale-item-row');
            newRow.innerHTML = `
                <td>
                    <select name="sale_items[${itemIndex}][product_id]" class="form-control product-select" required>
                        <option value="">Select Product</option>
                        @forelse ($products as $product)
                            <option value="{{ $product->id }}" data-stock="{{ optional($product->inventory)->quantity ?? 0 }}" data-unit-price="{{ $product->unit_price ?? 0 }}">{{ $product->name }} (Stock: {{ optional($product->inventory)->quantity ?? 0 }})</option>
                        @empty
                            <option value="">No products available</option>
                        @endforelse
                    </select>
                    <div class="text-danger stock-error"></div>
                </td>
                <td>
                    <input type="number" name="sale_items[${itemIndex}][quantity]" class="form-control quantity-input" min="1" value="1" required>
                </td>
                <td>
                    <input type="number" name="sale_items[${itemIndex}][unit_price]" class="form-control unit-price" step="0.01" min="0" readonly required>
                </td>
                <td>
                    <input type="number" name="sale_items[${itemIndex}][discount]" class="form-control discount-input" step="0.01" min="0" value="0">
                </td>
                <td>
                    <select name="sale_items[${itemIndex}][discount_type]" class="form-control discount-type">
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
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
            validateForm();
        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-item')) {
                console.log('Remove item clicked');
                const rows = document.querySelectorAll('.sale-item-row');
                const row = e.target.closest('tr');

                if (rows.length === 1) {
                    // Reset fields for single item
                    const productSelect = row.querySelector('.product-select');
                    const quantityInput = row.querySelector('.quantity-input');
                    const unitPriceInput = row.querySelector('.unit-price');
                    const discountInput = row.querySelector('.discount-input');
                    const discountType = row.querySelector('.discount-type');
                    const subtotalInput = row.querySelector('.subtotal');
                    const stockError = row.querySelector('.stock-error');

                    productSelect.value = '';
                    quantityInput.value = '1';
                    unitPriceInput.value = '';
                    discountInput.value = '0';
                    discountType.value = 'fixed';
                    subtotalInput.value = '';
                    stockError.textContent = '';
                    stockError.style.display = 'none';
                    toggleRowFields(row, false);
                    console.log('Single item reset');
                } else {
                    // Remove row for multiple items
                    row.remove();
                    console.log('Row removed');
                }

                updateTotalAmount();
                validateForm();
            }
        });

        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('product-select') || 
                e.target.classList.contains('quantity-input') || 
                e.target.classList.contains('discount-input') || 
                e.target.classList.contains('discount-type')) {
                console.log('Input changed:', e.target.className, e.target.value);
                const row = e.target.closest('tr');
                const productSelect = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const unitPriceInput = row.querySelector('.unit-price');
                const discountInput = row.querySelector('.discount-input');
                const discountType = row.querySelector('.discount-type');
                const subtotalInput = row.querySelector('.subtotal');
                const stockError = row.querySelector('.stock-error');

                if (e.target.classList.contains('product-select') && productSelect.value) {
                    const selectedOption = productSelect.selectedOptions[0];
                    const unitPrice = parseFloat(selectedOption.dataset.unitPrice) || 0;
                    const stock = parseInt(selectedOption.dataset.stock) || 0;
                    console.log('Product selected - Unit Price:', unitPrice, 'Stock:', stock);
                    unitPriceInput.value = unitPrice.toFixed(2);

                    // Handle stock status
                    if (stock <= 0) {
                        stockError.textContent = 'The product is out of stock.';
                        stockError.style.display = 'block';
                        toggleRowFields(row, true);
                    } else {
                        stockError.textContent = '';
                        stockError.style.display = 'none';
                        toggleRowFields(row, false);

                        // Validate stock via AJAX
                        axios.post('{{ route("sales.check-stock") }}', {
                            product_id: productSelect.value,
                            quantity: parseInt(quantityInput.value) || 1
                        })
                        .then(response => {
                            console.log('Stock check success:', response.data);
                            stockError.textContent = '';
                            stockError.style.display = 'none';
                            validateForm();
                        })
                        .catch(error => {
                            console.error('Stock check error:', error.response?.data);
                            stockError.textContent = error.response?.data?.error || 'Stock check failed';
                            stockError.style.display = 'block';
                            toggleRowFields(row, true);
                            validateForm();
                        });
                    }
                }

                // Calculate subtotal (only if not out of stock)
                if (quantityInput.value && unitPriceInput.value && !quantityInput.hasAttribute('readonly')) {
                    const baseAmount = parseInt(quantityInput.value) * parseFloat(unitPriceInput.value);
                    const discountValue = parseFloat(discountInput.value) || 0;
                    let subtotal = baseAmount;
                    if (discountValue > 0) {
                        if (discountType.value === 'percentage') {
                            subtotal = baseAmount * (1 - discountValue / 100);
                        } else {
                            subtotal = baseAmount - discountValue;
                        }
                    }
                    subtotalInput.value = Math.max(0, subtotal).toFixed(2);
                    console.log('Subtotal calculated:', subtotalInput.value, 'Discount Type:', discountType.value);

                    // Validate quantity against stock
                    if (productSelect.value) {
                        const stock = parseInt(productSelect.selectedOptions[0].dataset.stock) || 0;
                        if (parseInt(quantityInput.value) > stock) {
                            stockError.textContent = `Insufficient stock. Available: ${stock}.`;
                            stockError.style.display = 'block';
                            quantityInput.value = stock;
                            const baseAmount = parseInt(quantityInput.value) * parseFloat(unitPriceInput.value);
                            subtotalInput.value = baseAmount.toFixed(2);
                        }
                    }
                }

                updateTotalAmount();
                validateForm();
            }
        });

        // Prevent form submission if invalid
        document.getElementById('sale-form').addEventListener('submit', (e) => {
            if (!validateForm()) {
                e.preventDefault();
                console.log('Form submission prevented: Invalid stock');
            }
        });

        // Initialize subtotals and form validation on page load
        document.querySelectorAll('.sale-item-row').forEach(row => {
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');
            const unitPriceInput = row.querySelector('.unit-price');
            const discountInput = row.querySelector('.discount-input');
            const discountType = row.querySelector('.discount-type');
            const subtotalInput = row.querySelector('.subtotal');
            const stockError = row.querySelector('.stock-error');

            if (productSelect.value) {
                const stock = parseInt(productSelect.selectedOptions[0].dataset.stock) || 0;
                console.log('Initializing row - Stock:', stock);
                if (stock <= 0) {
                    stockError.textContent = 'The product is out of stock.';
                    stockError.style.display = 'block';
                    toggleRowFields(row, true);
                } else {
                    toggleRowFields(row, false);
                    if (quantityInput.value && unitPriceInput.value) {
                        const baseAmount = parseInt(quantityInput.value) * parseFloat(unitPriceInput.value);
                        const discountValue = parseFloat(discountInput.value) || 0;
                        let subtotal = baseAmount;
                        if (discountValue > 0) {
                            if (discountType.value === 'percentage') {
                                subtotal = baseAmount * (1 - discountValue / 100);
                            } else {
                                subtotal = baseAmount - discountValue;
                            }
                        }
                        subtotalInput.value = Math.max(0, subtotal).toFixed(2);
                    }
                }
            }
        });
        updateTotalAmount();
        validateForm();
    </script>
@endpush