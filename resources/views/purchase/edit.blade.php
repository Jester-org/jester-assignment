@extends('layouts.app')

@section('title', 'Edit Purchase')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            height: 2.5rem;
            padding: 0.5rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5rem;
        }
        .fade-transition {
            transition: opacity 0.3s ease, max-height 0.3s ease;
            opacity: 0;
            max-height: 0;
            overflow: hidden;
        }
        .fade-transition.show {
            opacity: 1;
            max-height: 500px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
        }
        th {
            background-color: #f3f4f6;
            text-align: left;
        }
    </style>
@endpush

@section('content')
    <div class="p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Purchase</h1>

        <a href="{{ route('purchases.index') }}" class="inline-block px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 mb-6">Back to Purchases</a>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" id="purchase-form" class="bg-white p-6 rounded-lg shadow-lg">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="w-full select2" required>
                    <option value="">Select Supplier</option>
                    @forelse ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @empty
                        <option value="">No suppliers available</option>
                    @endforelse
                </select>
                @error('supplier_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select name="user_id" id="user_id" class="w-full select2" required>
                    <option value="">Select User</option>
                    @forelse ($users as $user)
                        <option value="{{ $user->id }}" {{ $purchase->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @empty
                        <option value="">No users available</option>
                    @endforelse
                </select>
                @error('user_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="payment_method_id" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <select name="payment_method_id" id="payment_method_id" class="w-full select2">
                    <option value="">Select Payment Method</option>
                    @forelse ($paymentMethods as $paymentMethod)
                        <option value="{{ $paymentMethod->id }}" {{ $purchase->payment_method_id == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->name }}</option>
                    @empty
                        <option value="">No payment methods available</option>
                    @endforelse
                </select>
                @error('payment_method_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
                <input type="number" name="total_amount" id="total_amount" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" step="0.01" min="0" value="{{ $purchase->total_amount }}" readonly>
                @error('total_amount')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">Purchase Date</label>
                <input type="date" name="purchase_date" id="purchase_date" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ $purchase->purchase_date->format('Y-m-d') }}" required>
                @error('purchase_date')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full select2" required>
                    <option value="pending" {{ $purchase->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $purchase->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $purchase->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Purchase Items</h2>
            <table class="mb-6">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Apply Tax</th>
                        <th>Discount (%)</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="purchase-items">
                    @forelse ($purchase->purchaseItems as $index => $item)
                        <tr class="purchase-item-row">
                            <td>
                                <select name="purchase_items[{{ $index }}][product_id]" class="w-full product-select select2" required>
                                    <option value="">Select Product</option>
                                    @forelse ($products as $product)
                                        <option value="{{ $product->id }}"
                                                data-unit-price="{{ $product->unit_price ?? 0 }}"
                                                data-base-price="{{ $product->base_price ?? 0 }}"
                                                data-tax-rate="{{ optional($product->taxRate)->rate ?? 0 }}"
                                                {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                    @empty
                                        <option value="">No products available</option>
                                    @endforelse
                                </select>
                                @error("purchase_items.$index.product_id")
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="purchase_items[{{ $index }}][quantity]" class="w-full quantity-input" min="1" value="{{ $item->quantity }}" required>
                                @error("purchase_items.$index.quantity")
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="purchase_items[{{ $index }}][unit_price]" class="w-full unit-price" step="0.01" min="0" value="{{ $item->unit_price }}" readonly required>
                                @error("purchase_items.$index.unit_price")
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="checkbox" name="purchase_items[{{ $index }}][apply_tax]" class="apply-tax" value="1" {{ $item->apply_tax ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="number" name="purchase_items[{{ $index }}][discount]" class="w-full discount-input" step="0.01" min="0" max="100" value="{{ $item->discount ?? 0 }}">
                                @error("purchase_items.$index.discount")
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" class="w-full subtotal" step="0.01" value="{{ $item->subtotal }}" readonly>
                            </td>
                            <td>
                                <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-item">Remove</button>
                            </td>
                        </tr>
                    @empty
                        <tr class="purchase-item-row">
                            <td>
                                <select name="purchase_items[0][product_id]" class="w-full product-select select2" required>
                                    <option value="">Select Product</option>
                                    @forelse ($products as $product)
                                        <option value="{{ $product->id }}"
                                                data-unit-price="{{ $product->unit_price ?? 0 }}"
                                                data-base-price="{{ $product->base_price ?? 0 }}"
                                                data-tax-rate="{{ optional($product->taxRate)->rate ?? 0 }}">{{ $product->name }}</option>
                                    @empty
                                        <option value="">No products available</option>
                                    @endforelse
                                </select>
                                @error('purchase_items.0.product_id')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="purchase_items[0][quantity]" class="w-full quantity-input" min="1" value="1" required>
                                @error('purchase_items.0.quantity')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="purchase_items[0][unit_price]" class="w-full unit-price" step="0.01" min="0" readonly required>
                                @error('purchase_items.0.unit_price')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="checkbox" name="purchase_items[0][apply_tax]" class="apply-tax" value="1">
                            </td>
                            <td>
                                <input type="number" name="purchase_items[0][discount]" class="w-full discount-input" step="0.01" min="0" max="100" value="0">
                                @error('purchase_items.0.discount')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" class="w-full subtotal" step="0.01" readonly>
                            </td>
                            <td>
                                <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-item">Remove</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <button type="button" id="add-item" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 mb-6">Add Item</button>

            <div>
                <button type="submit" id="submit-purchase" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600" disabled>Update Purchase</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script>
        // Fallback for jQuery and Select2 if CDN fails
        window.jQuery || document.write('<script src="/js/jquery-3.6.0.min.js"><\/script>');
        window.Select2 || document.write('<script src="/js/select2.min.js"><\/script>');

        $(document).ready(function () {
            // Check if jQuery and Select2 are loaded
            if (typeof $.fn.select2 === 'undefined') {
                console.error('Select2 is not loaded. Check CDN or local fallback.');
                return;
            }

            // Initialize Select2 for all select elements
            try {
                $('.select2').select2({
                    width: '100%',
                    placeholder: 'Select an option',
                    allowClear: true,
                }).trigger('change');
                console.log('Select2 initialized for existing elements');
            } catch (e) {
                console.error('Select2 initialization failed:', e);
            }

            let itemIndex = {{ $purchase->purchaseItems->count() }};

            // Update total amount
            function updateTotalAmount() {
                let total = 0;
                $('.subtotal').each(function () {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#total_amount').val(total.toFixed(2));
                validateForm();
            }

            // Validate form
            function validateForm() {
                let isValid = true;
                let errors = [];

                // Check main form fields
                if (!$('#supplier_id').val()) {
                    isValid = false;
                    errors.push('Supplier is required.');
                }
                if (!$('#user_id').val()) {
                    isValid = false;
                    errors.push('User is required.');
                }
                if (!$('#purchase_date').val()) {
                    isValid = false;
                    errors.push('Purchase date is required.');
                }
                if (!$('#status').val()) {
                    isValid = false;
                    errors.push('Status is required.');
                }

                // Check purchase items
                $('.purchase-item-row').each(function (index) {
                    const productSelect = $(this).find('.product-select');
                    const quantityInput = $(this).find('.quantity-input');
                    if (!productSelect.val()) {
                        isValid = false;
                        errors.push(`Product for item ${index + 1} is required.`);
                    }
                    if (!quantityInput.val() || parseInt(quantityInput.val()) <= 0) {
                        isValid = false;
                        errors.push(`Quantity for item ${index + 1} must be greater than 0.`);
                    }
                });

                // Log validation result
                if (!isValid) {
                    console.log('Validation failed:', errors);
                } else {
                    console.log('Validation passed');
                }

                $('#submit-purchase').prop('disabled', !isValid);
                return isValid;
            }

            // Add new item row
            $('#add-item').click(function () {
                const newRow = `
                    <tr class="purchase-item-row">
                        <td>
                            <select name="purchase_items[${itemIndex}][product_id]" class="w-full product-select select2" required>
                                <option value="">Select Product</option>
                                @forelse ($products as $product)
                                    <option value="{{ $product->id }}"
                                            data-unit-price="{{ $product->unit_price ?? 0 }}"
                                            data-base-price="{{ $product->base_price ?? 0 }}"
                                            data-tax-rate="{{ optional($product->taxRate)->rate ?? 0 }}">{{ $product->name }}</option>
                                @empty
                                    <option value="">No products available</option>
                                @endforelse
                            </select>
                        </td>
                        <td>
                            <input type="number" name="purchase_items[${itemIndex}][quantity]" class="w-full quantity-input" min="1" value="1" required>
                        </td>
                        <td>
                            <input type="number" name="purchase_items[${itemIndex}][unit_price]" class="w-full unit-price" step="0.01" min="0" readonly required>
                        </td>
                        <td>
                            <input type="checkbox" name="purchase_items[${itemIndex}][apply_tax]" class="apply-tax" value="1">
                        </td>
                        <td>
                            <input type="number" name="purchase_items[${itemIndex}][discount]" class="w-full discount-input" step="0.01" min="0" max="100" value="0">
                        </td>
                        <td>
                            <input type="number" class="w-full subtotal" step="0.01" readonly>
                        </td>
                        <td>
                            <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-item">Remove</button>
                        </td>
                    </tr>
                `;
                $('#purchase-items').append(newRow);
                try {
                    $(`select[name="purchase_items[${itemIndex}][product_id]"]`).select2({
                        width: '100%',
                        placeholder: 'Select Product',
                        allowClear: true,
                    }).trigger('change');
                    console.log(`Select2 initialized for new item ${itemIndex}`);
                } catch (e) {
                    console.error(`Select2 initialization failed for new item ${itemIndex}:`, e);
                }
                itemIndex++;
                updateTotalAmount();
            });

            // Remove item row
            $(document).on('click', '.remove-item', function () {
                const row = $(this).closest('tr');
                if ($('.purchase-item-row').length === 1) {
                    row.find('.product-select').val('').trigger('change');
                    row.find('.quantity-input').val('1');
                    row.find('.unit-price').val('');
                    row.find('.apply-tax').prop('checked', false);
                    row.find('.discount-input').val('0');
                    row.find('.subtotal').val('');
                } else {
                    row.remove();
                }
                updateTotalAmount();
            });

            // Update row calculations
            $(document).on('change', '.product-select, .quantity-input, .apply-tax, .discount-input', function () {
                const row = $(this).closest('tr');
                const productSelect = row.find('.product-select');
                const quantityInput = row.find('.quantity-input');
                const unitPriceInput = row.find('.unit-price');
                const applyTax = row.find('.apply-tax');
                const discountInput = row.find('.discount-input');
                const subtotalInput = row.find('.subtotal');

                if (productSelect.val()) {
                    const selectedOption = productSelect.find('option:selected');
                    const basePrice = parseFloat(selectedOption.data('base-price')) || 0;
                    const unitPrice = parseFloat(selectedOption.data('unit-price')) || 0;
                    const price = applyTax.is(':checked') ? unitPrice : basePrice;
                    unitPriceInput.val(price.toFixed(2));

                    if (quantityInput.val()) {
                        let subtotal = parseInt(quantityInput.val()) * price;
                        const discount = parseFloat(discountInput.val()) || 0;
                        if (discount > 0) {
                            subtotal = subtotal * (1 - discount / 100);
                        }
                        subtotalInput.val(Math.max(0, subtotal).toFixed(2));
                    }
                } else {
                    unitPriceInput.val('');
                    subtotalInput.val('');
                }
                updateTotalAmount();
            });

            // Initialize subtotals on page load
            $('.purchase-item-row').each(function () {
                const row = $(this);
                const productSelect = row.find('.product-select');
                const quantityInput = row.find('.quantity-input');
                const unitPriceInput = row.find('.unit-price');
                const applyTax = row.find('.apply-tax');
                const discountInput = row.find('.discount-input');
                const subtotalInput = row.find('.subtotal');

                if (productSelect.val()) {
                    const selectedOption = productSelect.find('option:selected');
                    const basePrice = parseFloat(selectedOption.data('base-price')) || 0;
                    const unitPrice = parseFloat(selectedOption.data('unit-price')) || 0;
                    const price = applyTax.is(':checked') ? unitPrice : basePrice;
                    unitPriceInput.val(price.toFixed(2));

                    if (quantityInput.val()) {
                        let subtotal = parseInt(quantityInput.val()) * price;
                        const discount = parseFloat(discountInput.val()) || 0;
                        if (discount > 0) {
                            subtotal = subtotal * (1 - discount / 100);
                        }
                        subtotalInput.val(Math.max(0, subtotal).toFixed(2));
                    }
                }
                productSelect.trigger('change'); // Ensure pre-filled values are processed
            });

            // Initial validation
            updateTotalAmount();
        });
    </script>
@endpush