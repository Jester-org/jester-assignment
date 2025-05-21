@extends('layouts.app')
@section('title', 'Report Index')
@push('styles')
@endpush
@section('content')
    <h1>Reports</h1>
    <a href="{{ route('reports.create') }}" class="btn btn-primary">Create Report</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Generated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->name }}</td>
                    <td>{{ ucfirst($report->type) }}</td>
                    <td>{{ $report->generated_at ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('reports.show', $report) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('reports.edit', $report) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('reports.destroy', $report) }}" method="POST" style="display:inline;">
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

