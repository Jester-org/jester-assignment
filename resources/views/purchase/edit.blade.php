@extends('layouts.app')
@section('title', 'Purchase Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Purchase</h1>
    <form action="{{ route('purchases.update', $purchase) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="purchase_date">Purchase Date</label>
            <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}" required>
        </div>
        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" min="0" value="{{ $purchase->total_amount }}" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" {{ $purchase->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $purchase->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $purchase->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

