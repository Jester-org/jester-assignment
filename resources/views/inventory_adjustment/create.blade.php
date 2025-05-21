@extends('layouts.app')
@section('title', 'Inventory Adjustment Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Inventory Adjustment</h1>
    <form action="{{ route('inventory-adjustments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="inventory_id">Inventory (Product)</label>
            <select name="inventory_id" id="inventory_id" class="form-control" required>
                @foreach ($inventories as $inventory)
                    <option value="{{ $inventory->id }}">{{ $inventory->product->name }} (Qty: {{ $inventory->quantity }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="adjustment_type">Adjustment Type</label>
            <select name="adjustment_type" id="adjustment_type" class="form-control" required>
                <option value="addition">Addition</option>
                <option value="reduction">Reduction</option>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label for="reason">Reason</label>
            <textarea name="reason" id="reason" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="adjustment_date">Adjustment Date</label>
            <input type="date" name="adjustment_date" id="adjustment_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

