@extends('layouts.app')
@section('title', 'Category Show')
@push('styles')
@endpush
@section('content')
    <h1>Category Details</h1>
    <p><strong>Name:</strong> {{ $category->name }}</p>
    <p><strong>Description:</strong> {{ $category->description ?? 'N/A' }}</p>
    <p><strong>Products:</strong> {{ $category->products->count() }}</p>
    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
@endsection
@push('scripts')
@endpush

