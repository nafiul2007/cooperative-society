@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Contribution</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('contributions.update', $contribution) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Amount -->
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" min="1" name="amount" id="amount"
                    class="form-control @error('amount') is-invalid @enderror"
                    value="{{ old('amount', $contribution->amount) }}" required>
                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Contribution Date -->
            <div class="mb-3">
                <label for="contribution_date" class="form-label">Contribution Date</label>
                <input type="date" name="contribution_date" id="contribution_date"
                    class="form-control @error('contribution_date') is-invalid @enderror"
                    value="{{ old('contribution_date', $contribution->contribution_date) }}" required>
                @error('contribution_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Existing Files -->
            <div class="mb-3">
                <label class="form-label">Existing Files</label>
                <ul>
                    @if($contribution->files)
                        @forelse ($contribution->files as $file)
                            <li>
                                <a href="{{ asset('storage/' . $file->file_path) }}"
                                    target="_blank">{{ basename($file->file_path) }}</a>
                            </li>
                        @endforeach
                    @else
                        <li>No files uploaded.</li>
                    @endif
                </ul>
            </div>

            <!-- Upload New Files -->
            <div class="mb-3">
                <label for="attachments" class="form-label">Upload New Attachments (optional)</label>
                <input type="file" name="attachments[]" id="attachments"
                    class="form-control @error('attachments.*') is-invalid @enderror" multiple>
                @error('attachments.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Contribution</button>
            <a href="{{ route('contributions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
