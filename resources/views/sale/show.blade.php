@extends('layouts.app')

@section('title', 'Sale Show')

@push('styles')
    
@endpush

@section('content')
    <h1>Sale Details</h1>
    <p><strong>Customer:</strong> {{ $sale->customer->name }}</p>
    <p><strong>User:</strong> {{ $sale->user->name }}</p>
    <p><strong>Total Amount:</strong> {{ $sale->total_amount }}</p>
    <p><strong>Sale Date:</strong> {{ $sale->sale_date }}</p>
    <h3>Sale Items</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->saleItems as $saleItem)
                <tr>
                    <td>{{ $saleItem->product->name ?? 'N/A' }}</td>
                    <td>{{ $saleItem->quantity }}</td>
                    <td>{{ $saleItem->unit_price }}</td>
                    <td>{{ $saleItem->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>
@endsection

@push('scripts')
    
@endpush
