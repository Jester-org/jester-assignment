@extends('layouts.app')
@section('title', 'Batch Index')
@push('styles')
@endpush
@section('content')
    <h1>Batches</h1>
    <a href="{{ route('batches.create') }}" class="btn btn-primary">Create Batch</a>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Batch Number</th>
                <th>Quantity</th>
                <th>Received At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($batches as $batch)
                <tr>
                    <td>{{ $batch->product->name ?? 'N/A' }}</td>
                    <td>{{ $batch->batch_number }}</td>
                    <td>{{ $batch->quantity }}</td>
                    <td>{{ $batch->received_at }}</td>
                    <td>
                        <a href="{{ route('batches.show', $batch) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('batches.edit', $batch) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('batches.destroy', $batch) }}" method="POST" style="display:inline;">
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

