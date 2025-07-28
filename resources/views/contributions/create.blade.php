@extends('layouts.app')

@section('header', 'Add Contribution')

@section('content')
    <div class="">
        <div class="d-flex justify-content-between align-items-center card-header">
            <span>Add Contribution</span>
            <div>
                <a href="{{ route('contributions.index') }}" class="back-action">Back</a>
            </div>
        </div>

        <div class="card-body">
            
            <div id="ajaxFileRemoveStatus"></div>
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

            <form method="POST" action="{{ route('contributions.store') }}" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf

                <div class="row mb-3">
                    <label for="amount" class="col-sm-3 col-form-label">Amount <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="number" step="0.01" name="amount" id="amount"
                            class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                        @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="contribution_date" class="col-sm-3 col-form-label">Contribution Date <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" name="contribution_date" id="contribution_date"
                            class="form-control @error('contribution_date') is-invalid @enderror"
                            value="{{ old('contribution_date') }}" required>
                        @error('contribution_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="files" class="col-sm-3 col-form-label">Upload Files</label>
                    <div class="col-sm-9">
                        <input type="file" name="files[]" id="files"
                            class="form-control @error('files.*') is-invalid @enderror" multiple>
                        @error('files.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted mt-1 d-block">
                            📁 Max 2 files (PDF, DOC, XLS, JPG, PNG) — 2MB each
                        </small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="offset-sm-3 col-sm-9">
                        <button type="submit" class="btn btn-primary save-action">Submit</button>
                        <button type="reset" class="btn btn-warning reset-action">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const fileInput = document.getElementById('files');

            fileInput.addEventListener('change', function() {
                document.getElementById('ajaxFileRemoveStatus').innerHTML = "";
                window.UploadValidation = @json(config('uploads'));
                const allowedExtensions = window.UploadValidation.allowed_extensions;
                const maxFileSize = window.UploadValidation.max_file_size;

                let isValid = true;
                let errorMessage = '';

                // Check max selected files (2 at a time)
                if (this.files.length > 2) {
                    isValid = false;
                    errorMessage = 'You can only select up to 2 files at once.';
                }

                // Check extension and size
                if (isValid) {
                    for (let file of this.files) {
                        const ext = file.name.split('.').pop().toLowerCase();
                        if (!allowedExtensions.includes(ext)) {
                            isValid = false;
                            errorMessage =
                                `File "${file.name}" has an invalid extension. Allowed: ${allowedExtensions.join(', ')}`;
                            break;
                        }

                        if (file.size > maxFileSize) {
                            isValid = false;
                            errorMessage =
                                `File "${file.name}" exceeds the maximum size of ${maxFileSize / 1024 / 1024} MB.`;
                            break;
                        }
                    }
                }

                if (!isValid) {
                    this.value = ''; // Reset the input
                    const errorHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${errorMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
                    document.getElementById('ajaxFileRemoveStatus').innerHTML = errorHtml;
                }
            });
        });
    </script>
@endpush