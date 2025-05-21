@extends('layouts.app')
@section('title', 'Tax Rate Show')
@push('styles')
@endpush
@section('content')
    <h1>Tax Rate Details</h1>
    <p><strong>Name:</strong> {{ $taxRate->name }}</p>
    <p><strong>Rate (%):</strong> {{ $taxRate->rate }}</p>
    <p><strong>Products:</strong> {{ $taxRate->products->count() }}</p>
    <a href="{{ route('tax-rates.edit', $taxRate) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('tax-rates.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

