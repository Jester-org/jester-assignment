@extends('layouts.app')
@section('title', 'Category Show')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush
@section('content')
    <h1 class="text-2xl font-bold mb-4">Category Details</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>Name:</strong> {{ $category->name }}</p>
        <p><strong>Description:</strong> {{ $category->description ?? 'N/A' }}</p>
        <p><strong>Products:</strong> {{ $category->products->count() }}</p>
        <p><strong>Promotions:</strong>
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
        </p>
        <a href="{{ route('categories.edit', $category) }}" class="px-4 py-2 bg-yellow-500 text-white rounded inline-block">Edit</a>
        <a href="{{ route('categories.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded inline-block">Back</a>
    </div>
@endsection
@push('scripts')
@endpush