@extends('layouts.app')
@section('title', 'Report Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Report</h1>
    <form action="{{ route('reports.update', $report) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $report->name }}" required>
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="sales" {{ $report->type == 'sales' ? 'selected' : '' }}>Sales</option>
                <option value="inventory" {{ $report->type == 'inventory' ? 'selected' : '' }}>Inventory</option>
                <option value="payment" {{ $report->type == 'payment' ? 'selected' : '' }}>Payment</option>
                <option value="user_activity" {{ $report->type == 'user_activity' ? 'selected' : '' }}>User Activity</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $report->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="generated_at">Generated At</label>
            <input type="datetime-local" name="generated_at" id="generated_at" class="form-control" value="{{ $report->generated_at ? $report->generated_at->format('Y-m-d\TH:i') : '' }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

