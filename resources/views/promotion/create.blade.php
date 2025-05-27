@extends('layouts.app')

@section('title', 'Create Promotion')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem;
            min-height: 2.5rem;
        }
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            height: 2.5rem;
            padding: 0.5rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5rem;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3b82f6;
            color: white;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
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
    </style>
@endpush

@section('content')
    <div class="p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Create Promotion</h1>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('promotions.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-lg">
            @csrf

            <!-- Products -->
            <div class="mb-6">
                <label for="products" class="block text-sm font-medium text-gray-700 mb-2">Products</label>
                <select name="products[]" id="products" class="w-full" multiple>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('products')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Categories -->
            <div class="mb-6">
                <label for="categories" class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                <select name="categories[]" id="categories" class="w-full" multiple>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('categories')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Promotion Type -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Promotion Type</label>
                <select name="type" id="type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="" disabled selected>Select Promotion Type</option>
                    <option value="discount">Discount</option>
                    <option value="buy_get_free">Buy and Get Free</option>
                </select>
                @error('type')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Discount Fields -->
            <div class="discount-fields fade-transition mb-6">
                <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-2">Discount Type</label>
                <select name="discount_type" id="discount_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="fixed">Fixed</option>
                    <option value="percentage">Percentage</option>
                </select>
                @error('discount_type')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="discount-fields fade-transition mb-6">
                <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">Discount Value</label>
                <input type="number" name="discount_value" id="discount_value" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" step="0.01" min="0">
                @error('discount_value')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Free Item -->
            <div class="free-item-fields fade-transition mb-6">
                <label for="free_item_id" class="block text-sm font-medium text-gray-700 mb-2">Free Item</label>
                <select name="free_item_id" id="free_item_id" class="w-full">
                    <option value="">Select Free Item</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('free_item_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Start Date -->
            <div class="mb-6">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                @error('start_date')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- End Date -->
            <div class="mb-6">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" id="end_date" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                @error('end_date')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Active -->
            <div class="mb-6">
                <label for="is_active" class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
                @error('is_active')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Create Promotion
                </button>
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
            $('#products, #categories').select2({
                placeholder: 'Select items',
                allowClear: true,
                width: '100%',
            });

            $('#type, #discount_type, #free_item_id').select2({
                placeholder: $(this).find('option:first').text(),
                allowClear: false,
                width: '100%',
            });

            // Toggle fields based on promotion type
            function toggleFields(type) {
                const discountFields = $('.discount-fields');
                const freeItemFields = $('.free-item-fields');

                if (type === 'discount') {
                    discountFields.addClass('show');
                    freeItemFields.removeClass('show');
                    $('#discount_type, #discount_value').prop('required', true).prop('disabled', false);
                    $('#free_item_id').prop('required', false).prop('disabled', true).val('');
                } else if (type === 'buy_get_free') {
                    discountFields.removeClass('show');
                    freeItemFields.addClass('show');
                    $('#discount_type, #discount_value').prop('required', false).prop('disabled', true).val('');
                    $('#free_item_id').prop('required', true).prop('disabled', false);
                } else {
                    discountFields.removeClass('show');
                    freeItemFields.removeClass('show');
                    $('#discount_type, #discount_value').prop('required', false).prop('disabled', true).val('');
                    $('#free_item_id').prop('required', false).prop('disabled', true).val('');
                }
            }

            $('#type').on('change', function () {
                toggleFields($(this).val());
            });

            // Initialize field visibility
            toggleFields($('#type').val());

            // Clear disabled fields on form submission
            $('form').on('submit', function () {
                $(':disabled').val('');
            });
        });
    </script>
@endpush