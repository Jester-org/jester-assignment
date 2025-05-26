@extends('layouts.app')
@section('title', 'Product Create')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush
@section('content')
    <h1 class="text-2xl font-bold mb-4">Create Product</h1>
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('products.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category_id" id="category_id" class="form-control w-full border-gray-300 rounded-md" required>
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="tax_rate_id" class="block text-sm font-medium text-gray-700">Tax Rate</label>
            <select name="tax_rate_id" id="tax_rate_id" class="form-control w-full border-gray-300 rounded-md" required>
                <option value="">Select Tax Rate</option>
                @foreach ($taxRates as $taxRate)
                    <option value="{{ $taxRate->id }}" data-rate="{{ $taxRate->rate }}">{{ $taxRate->display_name }}</option>
                @endforeach
            </select>
            @error('tax_rate_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="form-control w-full border-gray-300 rounded-md" value="{{ old('name') }}" required>
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" class="form-control w-full border-gray-300 rounded-md">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="base_price" class="block text-sm font-medium text-gray-700">Base Price</label>
            <input type="number" name="base_price" id="base_price" class="form-control w-full border-gray-300 rounded-md" step="0.01" value="{{ old('base_price') }}" required>
            @error('base_price')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="vat" class="block text-sm font-medium text-gray-700">VAT</label>
            <input type="number" id="vat" class="form-control w-full border-gray-300 rounded-md" step="0.01" readonly>
            @error('vat')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price (Before Promotions)</label>
            <input type="number" id="unit_price" class="form-control w-full border-gray-300 rounded-md" step="0.01" readonly>
            @error('unit_price')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="reorder_threshold" class="block text-sm font-medium text-gray-700">Reorder Threshold</label>
            <input type="number" name="reorder_threshold" id="reorder_threshold" class="form-control w-full border-gray-300 rounded-md" value="{{ old('reorder_threshold') }}" required>
            @error('reorder_threshold')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Create</button>
    </form>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const basePriceInput = document.getElementById('base_price');
        const taxRateSelect = document.getElementById('tax_rate_id');
        const vatInput = document.getElementById('vat');
        const unitPriceInput = document.getElementById('unit_price');

        function updateCalculations() {
            const basePrice = parseFloat(basePriceInput.value) || 0;
            const taxRate = parseFloat(taxRateSelect.selectedOptions[0]?.dataset.rate) || 0;
            const vat = basePrice * (taxRate / 100);
            const unitPrice = basePrice + vat;

            vatInput.value = vat.toFixed(2);
            unitPriceInput.value = unitPrice.toFixed(2);
        }

        basePriceInput.addEventListener('input', updateCalculations);
        taxRateSelect.addEventListener('change', updateCalculations);
        updateCalculations();
    });
</script>
@endpush