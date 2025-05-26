@extends('layouts.app')
@section('title', 'Promotion Show')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush
@section('content')
    <h1 class="text-2xl font-bold mb-4">Promotion Details</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>Targets:</strong>
            @if ($promotion->products->isNotEmpty() || $promotion->categories->isNotEmpty())
                <ul>
                    @foreach ($promotion->products as $product)
                        <li>{{ $product->name }} (Product)</li>
                    @endforeach
                    @foreach ($promotion->categories as $category)
                        <li>{{ $category->name }} (Category)</li>
                    @endforeach
                </ul>
            @else
                N/A
            @endif
        </p>
        <p><strong>Type:</strong> {{ ucfirst($promotion->type) }}</p>
        <p><strong>Details:</strong>
            @if ($promotion->type === 'discount')
                {{ $promotion->discount_type === 'fixed' ? 'Fixed: $' . number_format($promotion->discount_value, 2) : $promotion->discount_value . '%' }}
            @else
                Free Item: {{ $promotion->freeItem->name ?? 'N/A' }}
            @endif
        </p>
        <p><strong>Start Date:</strong> {{ $promotion->start_date->format('Y-m-d') }}</p>
        <p><strong>End Date:</strong> {{ $promotion->end_date->format('Y-m-d') }}</p>
        <p><strong>Active:</strong> {{ $promotion->is_active ? 'Yes' : 'No' }}</p>
        <a href="{{ route('promotions.edit', $promotion) }}" class="px-4 py-2 bg-yellow-500 text-white rounded inline-block">Edit</a>
        <a href="{{ route('promotions.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded inline-block">Back</a>
    </div>
@endsection
@push('scripts')
@endpush