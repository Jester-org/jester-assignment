@extends('layouts.app')
@section('title', 'Payment Method Index')
@push('styles')
@endpush
@section('content')
    <h1>Payment Methods</h1>
    <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">Create Payment Method</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paymentMethods as $paymentMethod)
                <tr>
                    <td>{{ $paymentMethod->name }}</td>
                    <td>{{ $paymentMethod->description ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('payment-methods.show', $paymentMethod) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('payment-methods.edit', $paymentMethod) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('payment-methods.destroy', $paymentMethod) }}" method="POST" style="display:inline;">
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

