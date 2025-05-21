@extends('layouts.app')
@section('title', 'Expiry Date Create')
@push('styles')
@endpush
@section('content')
    <h1>Create Expiry Date</h1>
    <form action="{{ route('expiry-dates.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="batch_id">Batch</label>
            <select name="batch_id" id="batch_id" class="form-control" required>
                @foreach ($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->batch_number }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="expiry_date">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
@push('scripts')
@endpush

