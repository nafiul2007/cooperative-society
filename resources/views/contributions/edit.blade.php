@extends('layouts.app')

@section('header', 'Edit Contribution')

@section('content')
    <div class="">

        <div class="d-flex justify-content-between align-items-center card-header">
            <span>Edit Contribution</span>
            <div>
                <a href="{{ route('contributions.index') }}" class="back-action">Back</a>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops! Please fix the errors below:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contributions.update', $contribution) }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- Amount -->
                <div class="row mb-3">
                    <label for="amount" class="col-sm-3 col-form-label">Amount <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="number" step="0.01" min="1" name="amount" id="amount"
                            class="form-control @error('amount') is-invalid @enderror"
                            value="{{ old('amount', $contribution->amount) }}" required>
                        @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Contribution Date -->
                <div class="row mb-3">
                    <label for="contribution_date" class="col-sm-3 col-form-label">Contribution Date <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" name="contribution_date" id="contribution_date"
                            class="form-control @error('contribution_date') is-invalid @enderror"
                            value="{{ old('contribution_date', $contribution->contribution_date) }}" required>
                        @error('contribution_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Files -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Files:</label>
                    <div class="col-sm-9">
                        @if ($contribution->files && $contribution->files->count())
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Type</th>
                                            <th>File Name</th>
                                            <th>Size</th>
                                            <th>Uploaded At</th>
                                            {{-- @if ($contribution->status === 'pending' && auth()->id() === $contribution->user_id)
                                                <th>Action</th>
                                            @endif --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contribution->files as $file)
                                            @php
                                                $extension = pathinfo(
                                                    $file->original_name ?? basename($file->file_path),
                                                    PATHINFO_EXTENSION,
                                                );
                                                $icon = match (strtolower($extension)) {
                                                    'pdf' => 'file-earmark-pdf',
                                                    'doc', 'docx' => 'file-earmark-word',
                                                    'xls', 'xlsx' => 'file-earmark-excel',
                                                    'png', 'jpg', 'jpeg', 'gif' => 'file-earmark-image',
                                                    default => 'file-earmark',
                                                };

                                            @endphp
                                            <tr>
                                                <td class="text-center"><i class="bi bi-{{ $icon }} text-primary fs-5"></i></td>
                                                <td>
                                                    <a href="{{ route('contribution-files.download', ['contributionId' => $contribution->id, 'filename' => basename($file->file_path)]) }}"
                                                        target="_blank" class="text-decoration-none">
                                                        {{ $file->original_name ?? basename($file->file_path) }}
                                                    </a>
                                                </td>
                                                <td>{{ round(round($file->file_size / 1024, 2) / 1024, 2) . 'MB' }}</td>
                                                <td>{{ $file->created_at->format('M d, Y h:i A') }}</td>
                                                {{-- @if ($contribution->status === 'pending' && auth()->id() === $contribution->user_id)
                                                    <td>
                                                        <form action="{{ route('contribution-files.destroy', $file->id) }}"
                                                            method="POST" onsubmit="return confirm('Delete this file?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </td>
                                                @endif --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="form-control text-muted">No files uploaded.</div>
                        @endif
                    </div>
                </div>


                <!-- Upload New Files -->
                <div class="row mb-3">
                    <label for="files" class="col-sm-3 col-form-label">Upload New Files (optional)</label>
                    <div class="col-sm-9">
                        <input type="file" name="files[]" id="files"
                            class="form-control @error('files.*') is-invalid @enderror" multiple>
                        @error('files.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="row mb-3">
                    <div class="offset-sm-3 col-sm-9">
                        <button type="submit" class="btn btn-primary save-action">Update Contribution</button>
                        <button type="reset" class="btn btn-warning reset-action reset-action">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
