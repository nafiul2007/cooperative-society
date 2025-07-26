@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Submit Contribution</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('contributions.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror">
            @error('amount') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Contribution Date</label>
            <input type="date" name="contribution_date" class="form-control @error('contribution_date') is-invalid @enderror">
            @error('contribution_date') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Files</label>
            <input type="file" name="attachments[]" class="form-control" multiple>
            @error('attachments.*') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
