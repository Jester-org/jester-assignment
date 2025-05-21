@extends('layouts.app')
@section('title', 'Promotion Index')
@push('styles')
@endpush
@section('content')
    <h1>Promotions</h1>
    <a href="{{ route('promotions.create') }}" class="btn btn-primary">Create Promotion</a>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Discount (%)</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotions as $promotion)
                <tr>
                    <td>{{ $promotion->product->name ?? 'N/A' }}</td>
                    <td>{{ $promotion->discount_percentage }}</td>
                    <td>{{ $promotion->start_date }}</td>
                    <td>{{ $promotion->end_date }}</td>
                    <td>
                        <a href="{{ route('promotions.show', $promotion) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" style="display:inline;">
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

