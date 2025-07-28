@extends('layouts.app')

@section('header', 'Contribution Details')

@section('content')
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Contribution Details</span>
        <div>
            @if ($contribution->status === 'pending' && $contribution->user_id === auth()->id())
                <a href="{{ route('contributions.edit', $contribution) }}" class="edit-action me-2">Edit</a>
            @endif
            <a href="{{ route('contributions.index') }}" class="back-action">Back</a>
        </div>
    </div>

    <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Amount -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Amount:</label>
            <div class="col-sm-9 pt-2">
                {{ number_format($contribution->amount, 2) }}
            </div>
        </div>

        <!-- Contribution Date -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Contribution Date:</label>
            <div class="col-sm-9 pt-2">
                {{ \Carbon\Carbon::parse($contribution->contribution_date)->format('d M Y') }}
            </div>
        </div>

        <!-- Status -->
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Status:</label>
            <div class="col-sm-9 pt-2">
                <span
                    class="badge bg-{{ $contribution->status === 'approved'
                        ? 'success'
                        : ($contribution->status === 'pending'
                            ? 'warning'
                            : 'danger') }}">
                    {{ ucfirst($contribution->status) }}
                </span>
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
                                    <tr>
                                        <td class="text-center"><i class="bi bi-{{ $icon }} text-primary fs-5"></i>
                                        </td>
                                        <td>
                                            <a href="{{ route('contribution-files.download', ['contributionId' => $contribution->id, 'filename' => basename($file->file_path)]) }}"
                                                target="_blank" class="text-decoration-none">
                                                {{ $file->original_name ?? basename($file->file_path) }}
                                            </a>
                                        </td>
                                        <td>{{ $size }}</td>
                                        <td>{{ $file->created_at->format('M d, Y h:i A') }}</td>
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

        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-9">
                @if ($contribution->status === 'pending' && Auth::user()->hasRole('admin'))
                    <!-- Approve Button -->
                    <button type="button" class="btn btn-success approve-action" data-bs-toggle="modal"
                        data-bs-target="#approveModal">Approve</button>

                    <!-- Reject Button -->
                    <button type="button" class="btn btn-danger ms-2 reject-action" data-bs-toggle="modal"
                        data-bs-target="#rejectModal">Reject</button>
            </div>
        </div>
        <!-- Approve Modal -->
        <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('contributions.approve', $contribution) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="approveModalLabel">Confirm Approve</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to approve this contribution?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Approve</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('contributions.reject', $contribution) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="rejectModalLabel">Confirm Reject</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to reject this contribution?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
@endsection
