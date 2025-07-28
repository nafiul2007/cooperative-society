@extends('layouts.app')

@section('header', 'Edit Contribution')

@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center card-header">
            <span>Edit Contribution</span>
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

            <form action="{{ route('contributions.update', $contribution) }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- Amount -->
                <div class="row mb-3">
                    <label for="amount" class="col-sm-3 col-form-label">
                        Amount <span class="text-danger">*</span>
                    </label>
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
                    <label for="contribution_date" class="col-sm-3 col-form-label">
                        Contribution Date <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-9">
                        <input type="date" name="contribution_date" id="contribution_date"
                            class="form-control @error('contribution_date') is-invalid @enderror"
                            value="{{ old('contribution_date', $contribution->contribution_date) }}" required>
                        @error('contribution_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Existing Files -->
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Files:</label>
                    <div class="col-sm-9" id="fileContainer">
                        @if ($contribution->files->count())
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle" id="uploadedFiles">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Type</th>
                                            <th>File Name</th>
                                            <th>Size</th>
                                            <th>Uploaded At</th>
                                            @if ($contribution->status === 'pending' && auth()->id() === $contribution->user_id)
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contribution->files as $file)
                                            @php
                                                $ext = pathinfo(
                                                    $file->original_name ?? basename($file->file_path),
                                                    PATHINFO_EXTENSION,
                                                );
                                                $icon = match (strtolower($ext)) {
                                                    'pdf' => 'file-earmark-pdf',
                                                    'doc', 'docx' => 'file-earmark-word',
                                                    'xls', 'xlsx' => 'file-earmark-excel',
                                                    'png', 'jpg', 'jpeg', 'gif' => 'file-earmark-image',
                                                    default => 'file-earmark',
                                                };
                                                //File Size
                                                $sizeInBytes = $file->file_size;
                                                if ($sizeInBytes >= 1024 * 1024) {
                                                    // Show in MB with 2 decimals
                                                    $size = round($sizeInBytes / (1024 * 1024), 2) . ' MB';
                                                } elseif ($sizeInBytes >= 1024) {
                                                    // Show in KB with 2 decimals
                                                    $size = round($sizeInBytes / 1024, 2) . ' KB';
                                                } else {
                                                    // Show in Bytes
                                                    $size = $sizeInBytes . ' B';
                                                }
                                            @endphp
                                            <tr id="file-row-{{ $file->id }}">
                                                <td class="text-center">
                                                    <i class="bi bi-{{ $icon }} text-primary fs-5"></i>
                                                </td>
                                                <td>
                                                    <a href="{{ route('contribution-files.download', [
                                                        'contributionId' => $contribution->id,
                                                        'filename' => basename($file->file_path),
                                                    ]) }}"
                                                        target="_blank" class="text-decoration-none">
                                                        {{ $file->original_name ?? basename($file->file_path) }}
                                                    </a>
                                                </td>
                                                <td>{{ $size }}</td>
                                                <td>
                                                    {{ $file->created_at->format('M d, Y h:i A') }}
                                                </td>
                                                @if ($contribution->status === 'pending' && auth()->id() === $contribution->user_id)
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-danger js-remove-file"
                                                            data-id="{{ $file->id }}"
                                                            data-url="{{ route('contribution-files.destroy', $file->id) }}">
                                                            Remove
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="form-control text-muted">
                                No files uploaded.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Upload New Files -->
                <div class="row mb-3">
                    <label for="files" class="col-sm-3 col-form-label">
                        Upload New Files (optional)
                    </label>
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

                <!-- Action Buttons -->
                <div class="row mb-3">
                    <div class="offset-sm-3 col-sm-9">
                        <button type="submit" class="btn btn-primary save-action">
                            Update Contribution
                        </button>
                        <button type="reset" class="btn btn-warning reset-action">
                            Reset
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteFileModal" tabindex="-1" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteFileModalLabel">Confirm File Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this file?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
                </div>
            </div>
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

                // Check total existing + new files
                const table = document.getElementById('uploadedFiles');
                if (isValid && table) {
                    const tbody = table.querySelector('tbody');
                    if (tbody) {
                        const rows = tbody.querySelectorAll('tr');
                        const totalFiles = rows.length + this.files.length;
                        if (totalFiles > 2) {
                            isValid = false;
                            errorMessage = 'Total uploaded files cannot exceed 2.';
                        }
                    }
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

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            let deleteUrl = '';
            let fileRowId = '';

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteFileModal'));
            const confirmBtn = document.getElementById('confirmDeleteBtn');

            // When clicking "Remove" button
            document.querySelectorAll('.js-remove-file').forEach(button => {
                button.addEventListener('click', function() {
                    deleteUrl = this.dataset.url;
                    fileRowId = 'file-row-' + this.dataset.id;
                    deleteModal.show();
                });
            });

            // When confirming inside modal
            confirmBtn.addEventListener('click', function() {
                loadOverlayLogo('on');
                fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    })
                    .then(response => {
                        return response.json().then(data => {
                            if (response.ok) {
                                // success
                                document.getElementById('ajaxFileRemoveStatus').innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
                                const row = document.getElementById(fileRowId);
                                const table = row.closest('table');
                                if (row) {
                                    row.remove();
                                    const remainingRows = table.querySelectorAll('tbody tr');
                                    if (remainingRows.length === 0) {
                                        table.remove(); // Remove entire table
                                        const fileContainer = document.getElementById(
                                            "fileContainer");
                                        fileContainer.innerHTML =
                                            `<div class="form-control text-muted">No files uploaded.</div>`;
                                    }
                                }
                            } else {
                                // error
                                let message = data.message || 'Something went wrong.';
                                let errors = data.errors || {};

                                let errorHtml = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Oops! Please fix the errors below:</strong><ul class="mb-0 mt-1">`;

                                if (Object.keys(errors).length > 0) {
                                    for (let key in errors) {
                                        errors[key].forEach(msg => {
                                            errorHtml += `<li>${msg}</li>`;
                                        });
                                    }
                                } else {
                                    errorHtml += `<li>${message}</li>`;
                                }

                                errorHtml += `</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;

                                document.getElementById('ajaxFileRemoveStatus').innerHTML =
                                    errorHtml;
                            }
                        });
                    })
                    .catch(error => {
                        console.error(error);
                        document.getElementById('ajaxFileRemoveStatus').innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Unexpected error occurred. Please try again later.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    })
                    .finally(() => {
                        deleteModal.hide();
                        loadOverlayLogo('off');
                    });
            });
        });
    </script>
@endpush
