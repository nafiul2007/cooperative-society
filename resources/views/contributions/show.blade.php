@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Contribution Details</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            <strong>Amount:</strong>
            <p>{{ number_format($contribution->amount, 2) }}</p>
        </div>

        <div class="mb-3">
            <strong>Contribution Date:</strong>
            <p>{{ \Carbon\Carbon::parse($contribution->contribution_date)->format('d M Y') }}</p>
        </div>

        <div class="mb-3">
            <strong>Status:</strong>
            <span
                class="badge bg-{{ $contribution->status === 'approved'
                    ? 'success'
                    : ($contribution->status === 'pending'
                        ? 'warning'
                        : 'danger') }}">
                {{ ucfirst($contribution->status) }}
            </span>
        </div>

        <div class="mb-3">
            <strong>Attachments:</strong>
            <ul>
                @if ($contribution->files && $contribution->files->count())
                    @foreach ($contribution->files as $file)
                        <li>
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">
                                {{ basename($file->file_path) }}
                            </a>
                        </li>
                    @endforeach
                @else
                    <li>No files uploaded.</li>
                @endif
            </ul>
        </div>

        <a href="{{ route('contributions.index') }}" class="btn btn-secondary">Back to Contributions</a>

        @if ($contribution->status === 'pending')
            @if ($contribution->user_id === auth()->id())
                <a href="{{ route('contributions.edit', $contribution) }}" class="btn btn-primary ms-2">Edit
                    Contribution</a>
            @endif
            @if (Auth::user()->hasRole('admin'))
                <!-- Approve Button -->
                <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                    Approve
                </button>

                <!-- Reject Button -->
                <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    Reject
                </button>

                <!-- Approve Modal -->
                <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('contributions.approve', $contribution) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="approveModalLabel">Confirm Approve</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
                <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('contributions.reject', $contribution) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rejectModalLabel">Confirm Reject</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
        @endif
    </div>
@endsection
