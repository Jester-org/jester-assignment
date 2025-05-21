@extends('layouts.app')
@section('title', 'Expiry Date Edit')
@push('styles')
@endpush
@section('content')
    <h1>Edit Expiry Date</h1>
    <form action="{{ route('expiry-dates.update', $expiryDate) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="batch_id">Batch</label>
            <select name="batch_id" id="batch_id" class="form-control" required>
                @foreach ($batches as $batch)
                    <option value="{{ $batch->id }}" {{ $expiryDate->batch_id == $batch->id ? 'selected' : '' }}>{{ $batch->batch_number }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="expiry_date">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date" class="form-control" value="{{ $expiryDate->expiry_date }}" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ $expiryDate->notes }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
@push('scripts')
@endpush

