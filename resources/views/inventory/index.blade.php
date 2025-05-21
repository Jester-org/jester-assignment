@extends('layouts.app')
@section('title', 'Inventory Index')
@push('styles')
@endpush
@section('content')
    <h1>Inventories</h1>
    <a href="{{ route('inventories.create') }}" class="btn btn-primary">Create Inventory</a>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventories as $inventory)
                <tr>
                    <td>{{ $inventory->product->name ?? 'N/A' }}</td>
                    <td>{{ $inventory->quantity }}</td>
                    <td>{{ $inventory->last_updated ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('inventories.show', $inventory) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('inventories.edit', $inventory) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('inventories.destroy', $inventory) }}" method="POST" style="display:inline;">
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

