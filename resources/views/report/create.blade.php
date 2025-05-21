@extends('layouts.app')
@section('title', 'Report Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Report</h1>
    <form action="{{ route('reports.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="sales">Sales</option>
                <option value="inventory">Inventory</option>
                <option value="payment">Payment</option>
                <option value="user_activity">User Activity</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="generated_at">Generated At</label>
            <input type="datetime-local" name="generated_at" id="generated_at" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

