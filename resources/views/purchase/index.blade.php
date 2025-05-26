@extends('layouts.app')

@section('title', 'Purchase Index')

@push('styles')
@endpush

@section('content')
    <h1>Purchases</h1>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary mb-3">Create Purchase</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Supplier</th>
                <th>Purchase Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Items Count</th>
                <th>Products</th> <!-- New column -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $purchase->purchase_date }}</td>
                    <td>{{ $purchase->total_amount }}</td>
                    <td>{{ ucfirst($purchase->status) }}</td>
                    <td>{{ $purchase->purchaseItems->count() }}</td>
                    <td>
                        @foreach ($purchase->purchaseItems as $item)
                            {{ $item->product->name ?? 'N/A' }}<br>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-info btn-sm">Show</a>
                        <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this purchase?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No purchases found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@push('scripts')
@endpush
