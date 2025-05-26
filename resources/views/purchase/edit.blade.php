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

        <form action="{{ route('purchases.update', $purchase) }}" method="POST" id="purchase-form" class="bg-white p-6 rounded-lg shadow-lg">
            @csrf
            @method('PUT')

            <!-- Supplier -->
            <div class="mb-6">
                <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="w-full" required>
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

            <!-- User -->
            <div class="mb-6">
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select name="user_id" id="user_id" class="w-full" required>
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

            <!-- Total Amount -->
            <div class="mb-6">
                <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
                <input type="number" name="total_amount" id="total_amount" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" step="0.01" min="0" value="{{ $purchase->total_amount }}" readonly>
                @error('total_amount')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Purchase Date -->
            <div class="mb-6">
                <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">Purchase Date</label>
                <input type="date" name="purchase_date" id="purchase_date" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ $purchase->purchase_date->format('Y-m-d') }}" required>
                @error('purchase_date')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full" required>
                    <option value="pending" {{ $purchase->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $purchase->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $purchase->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Purchase Items -->
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Purchase Items</h3>
            <table class="mb-6" id="purchase-items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Apply Tax</th>
                        <th>Discount (%)</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="purchase-items">
                    @forelse ($purchase->purchaseItems as $index => $item)
                        <tr class="purchase-item-row">
                            <td>
                                <select name="purchase_items[{{ $index }}][product_id]" class="w-full product-select" required>
                                    <option value="">Select Product</option>
                                    @forelse ($products as $product)
                                        <option value="{{ $product->id }}" data-base-price="{{ $product->base_price ?? 0 }}" data-tax-rate="{{ $product->tax_rate ?? 0 }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} (${{ number_format($product->base_price ?? 0, 2) }})</option>
                                    @empty
                                        <option value="">No products available</option>
                                    @endforelse
                                </select>
                                @error("purchase_items.{$index}.product_id")
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="purchase_items[{{ $index }}][quantity]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 quantity-input" min="1" value="{{ $item->quantity }}" required>
                                @error("purchase_items.{$index}.quantity")
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="checkbox" name="purchase_items[{{ $index }}][apply_tax]" class="apply-tax" value="1" {{ $item->apply_tax ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="number" name="purchase_items[{{ $index }}][discount]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 discount-input" min="0" max="100" step="0.01" value="{{ $item->discount ?? 0 }}">
                                @error("purchase_items.{$index}.discount")
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="purchase_items[{{ $index }}][unit_price]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 unit-price" step="0.01" min="0" value="{{ $item->unit_price }}" readonly required>
                                @error("purchase_items.{$index}.unit_price")
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" class="w-full border-gray-300 rounded-md shadow-sm subtotal" step="0.01" value="{{ $item->subtotal }}" readonly>
                            </td>
                            <td>
                                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 remove-item">Remove</button>
                            </td>
                        </tr>
                    @empty
                        <tr class="purchase-item-row">
                            <td>
                                <select name="purchase_items[0][product_id]" class="w-full product-select" required>
                                    <option value="">Select Product</option>
                                    @forelse ($products as $product)
                                        <option value="{{ $product->id }}" data-base-price="{{ $product->base_price ?? 0 }}" data-tax-rate="{{ $product->tax_rate ?? 0 }}">{{ $product->name }} (${{ number_format($product->base_price ?? 0, 2) }})</option>
                                    @empty
                                        <option value="">No products available</option>
                                    @endforelse
                                </select>
                                @error('purchase_items.0.product_id')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="purchase_items[0][quantity]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 quantity-input" min="1" value="1" required>
                                @error('purchase_items.0.quantity')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="checkbox" name="purchase_items[0][apply_tax]" class="apply-tax" value="1">
                            </td>
                            <td>
                                <input type="number" name="purchase_items[0][discount]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 discount-input" min="0" max="100" step="0.01" value="0">
                                @error('purchase_items.0.discount')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="purchase_items[0][unit_price]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 unit-price" step="0.01" min="0" readonly required>
                                @error('purchase_items.0.unit_price')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="number" class="w-full border-gray-300 rounded-md shadow-sm subtotal" step="0.01" readonly>
                            </td>
                            <td>
                                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 remove-item">Remove</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mb-6" id="add-item">Add Item</button>

            <div>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" id="submit-purchase">Update Purchase</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2 for dropdowns
            $('#supplier_id, #user_id, #status, .product-select').select2({
                placeholder: $(this).find('option:first').text(),
                allowClear: true,
                width: '100%',
            });

            let itemIndex = {{ $purchase->purchaseItems->count() ?: 1 }};

            function updateTotalAmount() {
                const subtotals = $('.subtotal');
                let total = 0;
                subtotals.each(function () {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#total_amount').val(total.toFixed(2));
            }

            function calculateRow(row) {
                const productSelect = row.find('.product-select');
                const quantityInput = row.find('.quantity-input');
                const applyTax = row.find('.apply-tax').is(':checked');
                const discountInput = row.find('.discount-input');
                const unitPriceInput = row.find('.unit-price');
                const subtotalInput = row.find('.subtotal');

                if (productSelect.val()) {
                    const basePrice = parseFloat(productSelect.find(':selected').data('base-price')) || 0;
                    const taxRate = parseFloat(productSelect.find(':selected').data('tax-rate')) || 0;
                    let unitPrice = basePrice;

                    // Apply tax if checked
                    if (applyTax && taxRate > 0) {
                        unitPrice = basePrice * (1 + taxRate / 100);
                    }

                    // Apply discount
                    const discount = parseFloat(discountInput.val()) || 0;
                    if (discount > 0) {
                        unitPrice = unitPrice * (1 - discount / 100);
                    }

                    unitPriceInput.val(unitPrice.toFixed(2));

                    // Calculate subtotal
                    if (quantityInput.val()) {
                        const quantity = parseInt(quantityInput.val()) || 1;
                        const subtotal = quantity * unitPrice;
                        subtotalInput.val(Math.max(0, subtotal).toFixed(2));
                    }
                }

                updateTotalAmount();
            }

            function toggleRowFields(row) {
                const quantityInput = row.find('.quantity-input');
                const unitPriceInput = row.find('.unit-price');
                const subtotalInput = row.find('.subtotal');
                const discountInput = row.find('.discount-input');

                quantityInput.removeAttr('readonly');
                unitPriceInput.attr('readonly', 'readonly');
                discountInput.removeAttr('readonly');
                if (!quantityInput.val()) {
                    quantityInput.val(1);
                }
                if (!discountInput.val()) {
                    discountInput.val(0);
                }
            }

            $('#add-item').on('click', function () {
                const tbody = $('#purchase-items');
                const newRow = $(`
                    <tr class="purchase-item-row">
                        <td>
                            <select name="purchase_items[${itemIndex}][product_id]" class="w-full product-select" required>
                                <option value="">Select Product</option>
                                @forelse ($products as $product)
                                    <option value="{{ $product->id }}" data-base-price="{{ $product->base_price ?? 0 }}" data-tax-rate="{{ $product->tax_rate ?? 0 }}">{{ $product->name }} (${{ number_format($product->base_price ?? 0, 2) }})</option>
                                @empty
                                    <option value="">No products available</option>
                                @endforelse
                            </select>
                        </td>
                        <td>
                            <input type="number" name="purchase_items[${itemIndex}][quantity]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 quantity-input" min="1" value="1" required>
                        </td>
                        <td>
                            <input type="checkbox" name="purchase_items[${itemIndex}][apply_tax]" class="apply-tax" value="1">
                        </td>
                        <td>
                            <input type="number" name="purchase_items[${itemIndex}][discount]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 discount-input" min="0" max="100" step="0.01" value="0">
                        </td>
                        <td>
                            <input type="number" name="purchase_items[${itemIndex}][unit_price]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 unit-price" step="0.01" min="0" readonly required>
                        </td>
                        <td>
                            <input type="number" class="w-full border-gray-300 rounded-md shadow-sm subtotal" step="0.01" readonly />
                        </td>
                        <td>
                            <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 remove-item">Remove</button>
                        </td>
                    </tr>
                `);
                tbody.append(newRow);
                newRow.find('.product-select').select2({
                    placeholder: 'Select Product',
                    allowClear: true,
                    width: '100%',
                });
                itemIndex++;
                updateTotalAmount();
            });

            $(document).on('click', '.remove-item', function () {
                const rows = $('.purchase-item-row');
                const row = $(this).closest('tr');

                if (rows.length === 1) {
                    // Reset fields for single item
                    row.find('.product-select').val('').trigger('change');
                    row.find('.quantity-input').val('1');
                    row.find('.apply-tax').prop('checked', false);
                    row.find('.discount-input').val('0');
                    row.find('.unit-price').val('');
                    row.find('.subtotal').val('');
                    toggleRowFields(row);
                } else {
                    // Remove row for multiple items
                    row.remove();
                }
                updateTotalAmount();
            });

            $(document).on('change', '.product-select, .quantity-input, .apply-tax, .discount-input', function () {
                const row = $(this).closest('tr');
                calculateRow(row);
            });

            // Initialize total amount and calculate existing rows
            $('.purchase-item-row').each(function () {
                calculateRow($(this));
            });
        });
    </script>
@endpush