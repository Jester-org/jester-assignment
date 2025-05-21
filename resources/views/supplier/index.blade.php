@extends('layouts.app')
@section('title', 'Supplier Index')
@push('styles')
@endpush
@section('content')
    <h1>Suppliers</h1>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Create Supplier</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Purchases</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact_name ?? 'N/A' }}</td>
                    <td>{{ $supplier->email ?? 'N/A' }}</td>
                    <td>{{ $supplier->phone ?? 'N/A' }}</td>
                    <td>{{ $supplier->purchases->count() }}</td>
                    <td>
                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display:inline;">
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

