@extends('layouts.app')
@section('title', 'Sale Item Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Sale Item</h1>
    <form action="{{ route('sale-items.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="sale_id">Sale</label>
            <select name="sale_id" id="sale_id" class="form-control" required>
                @foreach ($sales as $sale)
                    <option value="{{ $sale->id }}">Sale #{{ $sale->id }}</option>
                @endforeach
            </select>
            @error('sale_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name ?? 'Product #'.$product->id }}</option>
                @endforeach
            </select>
            @error('product_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="{{ old('quantity') }}" required>
            @error('quantity')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="unit_price">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" step="0.01" value="{{ old('unit_price') }}" required>
            @error('unit_price')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="subtotal">Subtotal</label>
            <input type="number" name="subtotal" id="subtotal" class="form-control" step="0.01" value="{{ old('subtotal') }}" readonly>
            @error('subtotal')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
    <script>
        // Auto-calculate subtotal
        function updateSubtotal() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            const subtotal = quantity * unitPrice;
            document.getElementById('subtotal').value = subtotal.toFixed(2);
        }

        document.getElementById('quantity').addEventListener('input', updateSubtotal);
        document.getElementById('unit_price').addEventListener('input', updateSubtotal);

        // Real-time stock validation
        document.getElementById('quantity').addEventListener('input', function() {
            const productId = document.getElementById('product_id').value;
            const quantity = this.value;

            if (productId && quantity) {
                fetch('/sales/check-stock', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: productId, quantity: quantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        document.getElementById('quantity').value = '';
                        updateSubtotal();
                    }
                })
                .catch(error => {
                    console.error('Error checking stock:', error);
                });
            }
        });
    </script>
@endpush