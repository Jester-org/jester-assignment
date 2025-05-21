@extends('layouts.app')
@section('title', 'Product Index')
@push('styles')
@endpush
@section('content')
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Create Product</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Barcode</th>
                <th>Unit Price</th>
                <th>Stock Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>{{ $product->barcode }}</td>
                    <td>${{ number_format($product->unit_price, 2) }}</td>
                    <td>
                        @if ($product->low_stock)
                            <span class="text-danger">Low Stock ({{ $product->inventory->quantity ?? 0 }})</span>
                        @else
                            {{ $product->inventory->quantity ?? 0 }} in stock
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@push('scripts')
@endpush

