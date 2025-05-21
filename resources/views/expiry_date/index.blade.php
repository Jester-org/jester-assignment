@extends('layouts.app')
@section('title', 'Expiry Date Index')
@push('styles')
@endpush
@section('content')
    <h1>Expiry Dates</h1>
    <a href="{{ route('expiry-dates.create') }}" class="btn btn-primary">Create Expiry Date</a>
    <table class="table">
        <thead>
            <tr>
                <th>Batch</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expiryDates as $expiryDate)
                <tr>
                    <td>{{ $expiryDate->batch->batch_number ?? 'N/A' }}</td>
                    <td>{{ $expiryDate->expiry_date }}</td>
                    <td>
                        <a href="{{ route('expiry-dates.show', $expiryDate) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('expiry-dates.edit', $expiryDate) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('expiry-dates.destroy', $expiryDate) }}" method="POST" style="display:inline;">
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

