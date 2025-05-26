@extends('layouts.app')
@section('title', 'Product Index')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush
@section('content')
    <h1 class="text-2xl font-bold mb-4">Products</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <a href="{{ route('products.create') }}" class="mb-4 px-4 py-2 bg-blue-500 text-white rounded inline-block">Create Product</a>
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2">Name</th>
                <th class="border border-gray-300 px-4 py-2">Category</th>
                <th class="border border-gray-300 px-4 py-2">Unit Price</th>
                <th class="border border-gray-300 px-4 py-2">Stock</th>
                <th class="border border-gray-300 px-4 py-2">Low Stock</th>
                <th class="border border-gray-300 px-4 py-2">Promotions</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $product->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $product->category ? $product->category->name : 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">${{ number_format($product->unit_price, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $product->stock_quantity }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $product->low_stock ? 'Yes' : 'No' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $product->promotion_details }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('products.show', $product) }}" class="px-2 py-1 bg-blue-500 text-white rounded">Show</a>
                        <a href="{{ route('products.edit', $product) }}" class="px-2 py-1 bg-yellow-500 text-white rounded">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="border border-gray-300 px-4 py-2 text-center">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $products->links() }}
@endsection
@push('scripts')
@endpush