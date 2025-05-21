@extends('layouts.app')

@section('title', 'Sale Item Index')

@push('styles')
    
@endpush

@section('content')
    <h1>Sale Items</h1>
    <a href="{{ route('sale-items.create') }}" class="btn btn-primary">Create Sale Item</a>
    <table class="table">
        <thead>
            <tr>
                <th>Sale</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($saleItems as $saleItem)
                <tr>
                    <td>{{ $saleItem->sale->id }}</td>
                    <td>{{ $saleItem->product->name ?? 'N/A' }}</td>
                    <td>{{ $saleItem->quantity }}</td>
                    <td>{{ $saleItem->unit_price }}</td>
                    <td>{{ $saleItem->subtotal }}</td>
                    <td>
                        <a href="{{ route('sale-items.show', $saleItem) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('sale-items.edit', $saleItem) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('sale-items.destroy', $saleItem) }}" method="POST" style="display:inline;">
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