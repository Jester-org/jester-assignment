@extends('layouts.app')
@section('title', 'Category Index')
@push('styles')
@endpush
@section('content')
    <h1>Categories</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">Create Category</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? 'N/A' }}</td>
                    <td>{{ $category->products->count() }}</td>
                    <td>
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
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

