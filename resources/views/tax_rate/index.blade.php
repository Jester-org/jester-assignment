@extends('layouts.app')
@section('title', 'Tax Rate Index')
@push('styles')
@endpush
@section('content')
    <h1>Tax Rates</h1>
    <a href="{{ route('tax-rates.create') }}" class="btn btn-primary">Create Tax Rate</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Rate (%)</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($taxRates as $taxRate)
                <tr>
                    <td>{{ $taxRate->name }}</td>
                    <td>{{ $taxRate->rate }}</td>
                    <td>{{ $taxRate->products->count() }}</td>
                    <td>
                        <a href="{{ route('tax-rates.show', $taxRate) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('tax-rates.edit', $taxRate) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('tax-rates.destroy', $taxRate) }}" method="POST" style="display:inline;">
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

