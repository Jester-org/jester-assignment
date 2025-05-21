@extends('layouts.app')
@section('title', 'Inventory Adjustment Index')
@push('styles')
@endpush
@section('content')
    <h1>Inventory Adjustments</h1>
    <a href="{{ route('inventory-adjustments.create') }}" class="btn btn-primary">Create Adjustment</a>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Adjustment Type</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventoryAdjustments as $inventoryAdjustment)
                <tr>
                    <td>{{ $inventoryAdjustment->inventory->product->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($inventoryAdjustment->adjustment_type) }}</td>
                    <td>{{ $inventoryAdjustment->quantity }}</td>
                    <td>{{ $inventoryAdjustment->reason ?? 'N/A' }}</td>
                    <td>{{ $inventoryAdjustment->adjustment_date }}</td>
                    <td>
                        <a href="{{ route('inventory-adjustments.show', $inventoryAdjustment) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('inventory-adjustments.edit', $inventoryAdjustment) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('inventory-adjustments.destroy', $inventoryAdjustment) }}" method="POST" style="display:inline;">
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

