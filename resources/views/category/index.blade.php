@extends('layouts.app')
@section('title', 'Category Index')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush
@section('content')
    <h1 class="text-2xl font-bold mb-4">Categories</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <a href="{{ route('categories.create') }}" class="mb-4 px-4 py-2 bg-blue-500 text-white rounded inline-block">Create Category</a>
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2">Name</th>
                <th class="border border-gray-300 px-4 py-2">Description</th>
                <th class="border border-gray-300 px-4 py-2">Products</th>
                <th class="border border-gray-300 px-4 py-2">Promotions</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $category->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $category->description ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $category->products->count() }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if ($category->promotions->isNotEmpty())
                            {{ $category->promotions->map(function ($promotion) {
                                return $promotion->type === 'discount'
                                    ? ($promotion->discount_type === 'fixed'
                                        ? 'Fixed: $' . number_format($promotion->discount_value, 2)
                                        : $promotion->discount_value . '%') . ' (until ' . $promotion->end_date->format('Y-m-d') . ')'
                                    : 'Buy and Get Free: ' . ($promotion->freeItem->name ?? 'N/A') . ' (until ' . $promotion->end_date->format('Y-m-d') . ')';
                            })->implode('; ') }}
                        @else
                            None
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('categories.show', $category) }}" class="px-2 py-1 bg-blue-500 text-white rounded">Show</a>
                        <a href="{{ route('categories.edit', $category) }}" class="px-2 py-1 bg-yellow-500 text-white rounded">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border border-gray-300 px-4 py-2 text-center">No categories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $categories->links() }}
@endsection
@push('scripts')
@endpush