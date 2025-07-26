@extends('layouts.app')

@section('content')
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Member Details</h5>
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
            <div class="col-sm-9 pt-2">{{ $member->name ?? '-' }}</div>
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
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9 pt-2">
                <a href="{{ route('members.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('members.edit', $member) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </div>
@endsection
