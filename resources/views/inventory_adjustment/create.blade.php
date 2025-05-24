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
            @error('inventory_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="adjustment_type">Adjustment Type</label>
            <select name="adjustment_type" id="adjustment_type" class="form-control" required>
                <option value="addition">Addition</option>
                <option value="reduction">Reduction</option>
            </select>
            @error('adjustment_type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required>
            @error('quantity')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="reason">Reason</label>
            <textarea name="reason" id="reason" class="form-control">{{ old('reason') }}</textarea>
            @error('reason')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="adjustment_date">Adjustment Date</label>
            <input type="date" name="adjustment_date" id="adjustment_date" class="form-control" value="{{ old('adjustment_date') }}" required>
            @error('adjustment_date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
    <script>
        document.getElementById('quantity').addEventListener('input', function() {
            const inventoryId = document.getElementById('inventory_id').value;
            const adjustmentType = document.getElementById('adjustment_type').value;
            const quantity = this.value;

            if (inventoryId && adjustmentType === 'reduction' && quantity) {
                fetch('/inventory-adjustments/check-stock', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ inventory_id: inventoryId, quantity: Math.abs(quantity) })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        document.getElementById('quantity').value = '';
                    }
                })
                .catch(error => {
                    console.error('Error checking stock:', error);
                });
            }
        });
    </script>
@endpush