 @extends('layouts.app')

 @section('title', 'Create Sale')

 @push('styles')
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
     <style>
         .select2-container--default .select2-selection--single {
             border: 1px solid #ced4da;
             border-radius: 0.25rem;
             height: 38px;
             padding: 0.375rem 0.75rem;
         }
         .select2-container--default .select2-selection--single .select2-selection__rendered {
             line-height: 24px;
         }
         .stock-error {
             display: none;
             color: #dc3545;
             font-size: 0.875rem;
         }
     </style>
 @endpush

 @section('content')
     <div class="container p-6">
         <h1 class="text-2xl font-bold mb-3">Create Sale</h1>
         <a href="{{ route('sales.index') }}" class="btn btn-secondary mb-3">Back to Sales</a>
         <form action="{{ route('sales.store') }}" method="POST" id="sale-form">
             @csrf
             <div class="mb-3">
                 <label for="customer_id" class="form-label">Customer</label>
                 <select name="customer_id" id="customer_id" class="form-control select2" required>
                     <option value="">Select Customer</option>
                     @forelse ($customers as $customer)
                         <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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
                 <select name="user_id" id="user_id" class="form-control select2" required>
                     <option value="">Select User</option>
                     @forelse ($users as $user)
                         <option value="{{ $user->id }}">{{ $user->name }}</option>
                     @empty
                         <option value="">No users available</option>
                     @endforelse
                 </select>
                 @error('user_id')
                     <div class="text-danger">{{ $message }}</div>
                 @enderror
             </div>
             <div class="mb-3">
                 <label for="payment_method_id" class="form-label">Payment Method</label>
                 <select name="payment_method_id" id="payment_method_id" class="form-control select2">
                     <option value="">Select Payment Method</option>
                     @forelse ($paymentMethods as $paymentMethod)
                         <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                     @empty
                         <option value="">No payment methods available</option>
                     @endforelse
                 </select>
                 @error('payment_method_id')
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
                 <input type="date" name="sale_date" id="sale_date" class="form-control" required>
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
                     <tr class="sale-item-row">
                         <td>
                             <select name="sale_items[0][product_id]" class="form-control product-select select2" required>
                                 <option value="">Select Product</option>
                                 @forelse ($products as $product)
                                     <option value="{{ $product->id }}" 
                                             data-unit-price="{{ $product->unit_price ?? 0 }}" 
                                             data-stock="{{ optional($product->inventory)->quantity ?? 0 }}">{{ $product->name }} (Stock: {{ optional($product->inventory)->quantity ?? 0 }})</option>
                                 @empty
                                     <option value="">No products available</option>
                                 @endforelse
                             </select>
                             <div class="stock-error"></div>
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
                 </tbody>
             </table>
             <button type="button" class="btn btn-primary" id="add-item">Add Item</button>
             <div class="mt-3">
                 <button type="submit" class="btn btn-success" id="submit-sale" disabled>Create Sale</button>
             </div>
         </form>
     </div>
 @endsection

 @push('scripts')
     <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
     <script>
         $(document).ready(function() {
             // Initialize Select2 for all dropdowns
             $('.select2').select2({
                 width: '100%',
                 placeholder: 'Select an option',
                 allowClear: true
             });

             console.log('Create sale script loaded');

             const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
             if (!csrfToken) {
                 console.error('CSRF token not found');
             }
             axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

             let itemIndex = 1;

             function updateTotalAmount() {
                 let total = 0;
                 $('.subtotal').each(function() {
                     total += parseFloat($(this).val()) || 0;
                 });
                 $('#total_amount').val(total.toFixed(2));
                 validateForm();
             }

             function toggleRowFields(row, isOutOfStock) {
                 const quantityInput = row.find('.quantity-input');
                 const unitPriceInput = row.find('.unit-price');
                 const discountInput = row.find('.discount-input');
                 const discountType = row.find('.discount-type');
                 const subtotalInput = row.find('.subtotal');

                 if (isOutOfStock) {
                     quantityInput.prop('readonly', true);
                     unitPriceInput.prop('readonly', true);
                     discountInput.prop('readonly', true);
                     discountType.prop('disabled', true);
                     quantityInput.val('');
                     discountInput.val('0');
                     subtotalInput.val('');
                 } else {
                     quantityInput.prop('readonly', false);
                     unitPriceInput.prop('readonly', true); // Keep unit price readonly
                     discountInput.prop('readonly', false);
                     discountType.prop('disabled', false);
                     if (!quantityInput.val()) {
                         quantityInput.val(1);
                     }
                 }
             }

             function validateForm() {
                 let isValid = true;
                 $('.sale-item-row').each(function() {
                     const productSelect = $(this).find('.product-select');
                     const quantityInput = $(this).find('.quantity-input');
                     const stockError = $(this).find('.stock-error');

                     if (productSelect.val()) {
                         const stock = parseInt(productSelect.find('option:selected').data('stock')) || 0;
                         const quantity = parseInt(quantityInput.val()) || 0;

                         if (stock <= 0) {
                             stockError.text('The product is out of stock.').show();
                             isValid = false;
                         } else if (quantity > stock) {
                             stockError.text(`Insufficient stock. Available: ${stock}.`).show();
                             isValid = false;
                         } else {
                             stockError.text('').hide();
                         }
                     }
                 });

                 $('#submit-sale').prop('disabled', !isValid);
                 return isValid;
             }

             function updateRow(row) {
                 const productSelect = row.find('.product-select');
                 const quantityInput = row.find('.quantity-input');
                 const unitPriceInput = row.find('.unit-price');
                 const discountInput = row.find('.discount-input');
                 const discountType = row.find('.discount-type');
                 const subtotalInput = row.find('.subtotal');
                 const stockError = row.find('.stock-error');

                 if (productSelect.val()) {
                     const selectedOption = productSelect.find('option:selected');
                     const unitPrice = parseFloat(selectedOption.data('unit-price')) || 0;
                     const stock = parseInt(selectedOption.data('stock')) || 0;
                     unitPriceInput.val(unitPrice.toFixed(2));

                     if (stock <= 0) {
                         stockError.text('The product is out of stock.').show();
                         toggleRowFields(row, true);
                         subtotalInput.val('');
                     } else {
                         stockError.text('').hide();
                         toggleRowFields(row, false);

                         axios.post('{{ route("sales.check-stock") }}', {
                             product_id: productSelect.val(),
                             quantity: parseInt(quantityInput.val()) || 1
                         })
                         .then(response => {
                             stockError.text('').hide();
                             validateForm();
                         })
                         .catch(error => {
                             stockError.text(error.response?.data?.error || 'Stock check failed').show();
                             toggleRowFields(row, true);
                             validateForm();
                         });
                     }

                     if (quantityInput.val() && !quantityInput.prop('readonly')) {
                         const quantity = parseInt(quantityInput.val());
                         const discountValue = parseFloat(discountInput.val()) || 0;
                         let subtotal = quantity * unitPrice;

                         if (discountValue > 0) {
                             if (discountType.val() === 'fixed') {
                                 // Apply fixed discount per unit
                                 subtotal -= (discountValue * quantity);
                             } else if (discountType.val() === 'percentage') {
                                 // Apply percentage discount to total
                                 subtotal *= (1 - discountValue / 100);
                             }
                         }

                         subtotalInput.val(Math.max(0, subtotal).toFixed(2));

                         if (stock > 0 && quantity > stock) {
                             stockError.text(`Insufficient stock. Available: ${stock}.`).show();
                             quantityInput.val(stock);
                             let adjustedSubtotal = stock * unitPrice;
                             if (discountValue > 0) {
                                 if (discountType.val() === 'fixed') {
                                     adjustedSubtotal -= (discountValue * stock);
                                 } else {
                                     adjustedSubtotal *= (1 - discountValue / 100);
                                 }
                             }
                             subtotalInput.val(Math.max(0, adjustedSubtotal).toFixed(2));
                         }
                     }
                 } else {
                     unitPriceInput.val('');
                     subtotalInput.val('');
                     stockError.text('').hide();
                 }
                 updateTotalAmount();
             }

             $('#add-item').click(function() {
                 const newRow = $(`
                     <tr class="sale-item-row">
                         <td>
                             <select name="sale_items[${itemIndex}][product_id]" class="form-control product-select select2" required>
                                 <option value="">Select Product</option>
                                 @forelse ($products as $product)
                                     <option value="{{ $product->id }}" 
                                             data-unit-price="{{ $product->unit_price ?? 0 }}" 
                                             data-stock="{{ optional($product->inventory)->quantity ?? 0 }}">{{ $product->name }} (Stock: {{ optional($product->inventory)->quantity ?? 0 }})</option>
                                 @empty
                                     <option value="">No products available</option>
                                 @endforelse
                             </select>
                             <div class="stock-error"></div>
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
                     </tr>
                 `);
                 $('#sale-items').append(newRow);
                 newRow.find('.select2').select2({
                     width: '100%',
                     placeholder: 'Select Product',
                     allowClear: true
                 });
                 itemIndex++;
                 updateTotalAmount();
             });

             $(document).on('click', '.remove-item', function() {
                 const row = $(this).closest('tr');
                 if ($('.sale-item-row').length === 1) {
                     row.find('.product-select').val('').trigger('change');
                     row.find('.quantity-input').val('1');
                     row.find('.unit-price').val('');
                     row.find('.discount-input').val('0');
                     row.find('.discount-type').val('fixed');
                     row.find('.subtotal').val('');
                     row.find('.stock-error').text('').hide();
                     toggleRowFields(row, false);
                 } else {
                     row.remove();
                 }
                 updateTotalAmount();
             });

             $(document).on('change', '.product-select, .quantity-input, .discount-input, .discount-type', function() {
                 updateRow($(this).closest('.sale-item-row'));
             });

             $('#sale-form').submit(function(e) {
                 if (!validateForm()) {
                     e.preventDefault();
                 }
             });

             // Initialize existing rows
             $('.sale-item-row').each(function() {
                 updateRow($(this));
             });
         });
     </script>
 @endpush