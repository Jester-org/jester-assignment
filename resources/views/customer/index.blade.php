@extends('layouts.app')

@section('title', 'Customer Index')

@push('styles')
    
@endpush

@section('content')
    <h1>Customers</h1>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">Create Customer</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline;">
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