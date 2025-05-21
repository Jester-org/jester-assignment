@extends('layouts.app')

@section('title', 'Sale Index')

@push('styles')
    
@endpush

@section('content')
    <h1>Sales</h1>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">Create Sale</a>
    <table class="table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>User</th>
                <th>Total Amount</th>
                <th>Sale Date</th>
                <th>Items</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->customer->name }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>{{ $sale->total_amount }}</td>
                    <td>{{ $sale->sale_date }}</td>
                    <td>{{ $sale->saleItems->count() }}</td>
                    <td>
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" style="display:inline;">
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
