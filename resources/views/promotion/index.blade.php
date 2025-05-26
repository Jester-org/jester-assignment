@extends('layouts.app')
@section('title', 'Promotion Index')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush
@section('content')
    <h1 class="text-2xl font-bold mb-4">Promotions</h1>
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    <a href="{{ route('promotions.create') }}" class="mb-4 px-4 py-2 bg-blue-500 text-white rounded inline-block">Create Promotion</a>
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2">Targets</th>
                <th class="border border-gray-300 px-4 py-2">Type</th>
                <th class="border border-gray-300 px-4 py-2">Details</th>
                <th class="border border-gray-300 px-4 py-2">Start Date</th>
                <th class="border border-gray-300 px-4 py-2">End Date</th>
                <th class="border border-gray-300 px-4 py-2">Active</th>
                <th class="border border-gray-300 px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($promotions as $promotion)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">
                        @if ($promotion->products->isNotEmpty() || $promotion->categories->isNotEmpty())
                            @foreach ($promotion->products as $product)
                                {{ $product->name }} (Product)<br>
                            @endforeach
                            @foreach ($promotion->categories as $category)
                                {{ $category->name }} (Category)<br>
                            @endforeach
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ ucfirst($promotion->type) }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if ($promotion->type === 'discount')
                            {{ $promotion->discount_type === 'fixed' ? 'Fixed: $' . number_format($promotion->discount_value, 2) : $promotion->discount_value . '%' }}
                        @else
                            Free Item: {{ $promotion->freeItem->name ?? 'N/A' }}
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $promotion->start_date->format('Y-m-d') }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $promotion->end_date->format('Y-m-d') }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $promotion->is_active ? 'Yes' : 'No' }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('promotions.show', $promotion) }}" class="px-2 py-1 bg-blue-500 text-white rounded">Show</a>
                        <a href="{{ route('promotions.edit', $promotion) }}" class="px-2 py-1 bg-yellow-500 text-white rounded">Edit</a>
                        <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="border border-gray-300 px-4 py-2 text-center">No promotions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $promotions->links() }}
@endsection
@push('scripts')
@endpush