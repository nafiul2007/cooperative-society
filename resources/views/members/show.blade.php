@extends('layouts.app')

@section('header', 'Member Details')

@section('content')
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Member Details</span>
        <div>
            @if (Auth::user()->hasRole('admin'))
                <a href="{{ route('members.edit', $member) }}" class="edit-action me-2">Edit</a>
            @endif
            <a href="{{ route('members.index') }}" class="back-action">Back</a>
        </div>
    </div>

    <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Name:</label>
            <div class="col-sm-9 pt-2">
                {{ $member->name ?? '-' }}
                @if ($member->user->isAdmin())
                    <span class="badge bg-danger">Admin</span>
                @else
                    <span class="badge bg-primary">Member</span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Email:</label>
            <div class="col-sm-9 pt-2">{{ $member->user->email ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Phone:</label>
            <div class="col-sm-9 pt-2">{{ $member->mobile_number ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">NID:</label>
            <div class="col-sm-9 pt-2">{{ $member->nid ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Date of Birth:</label>
            <div class="col-sm-9 pt-2">
                {{ $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->format('F d, Y') : '-' }}
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">TIN:</label>
            <div class="col-sm-9 pt-2">{{ $member->tin ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Business Share:</label>
            <div class="col-sm-9 pt-2">{{ $member->business_share ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Status:</label>
            <div class="col-sm-9 pt-2">
                @if ($member->user->isActive())
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </div>
        </div>

        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-9">
                @if (Auth::user()->isAdmin()  && !$member->user->isAdmin())
                    @if ($member->user->isActive())
                        <!-- Disable button triggers modal -->
                        <button type="button" class="btn btn-danger reject-action" data-bs-toggle="modal"
                            data-bs-target="#disableConfirmModal">
                            Disable User
                        </button>
                    @else
                        <!-- Enable button -->
                        <button type="button" class="btn btn-success approve-action" data-bs-toggle="modal"
                            data-bs-target="#enableConfirmModal" data-action="">
                            Enable User
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="disableConfirmModal" tabindex="-1" aria-labelledby="disableConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="disableForm" method="POST" action="{{ route('members.disable', $member) }}">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="disableConfirmModalLabel">Confirm Disable</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to disable this member?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Disable</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enable Confirmation Modal -->
    <div class="modal fade" id="enableConfirmModal" tabindex="-1" aria-labelledby="enableConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="enableForm" method="POST" action="{{ route('members.enable', $member) }}">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="enableConfirmModalLabel">Confirm Enable</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to enable this member?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Enable</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
