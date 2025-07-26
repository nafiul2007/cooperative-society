@extends('layouts.app')

@section('content')
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Society Information</span>
        @if (Auth::user()->hasRole('admin'))
        <div >
            <a href="{{ route('society-info.edit') }}" class="edit-action">Edit</a>
        </div>
        @endif
    </div>

    <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Society Name:</label>
            <div class="col-sm-9 pt-2">{{ $societyInfo->name ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Registration No:</label>
            <div class="col-sm-9 pt-2">{{ $societyInfo->registration_no ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Address:</label>
            <div class="col-sm-9 pt-2">{{ $societyInfo->address ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Phone:</label>
            <div class="col-sm-9 pt-2">{{ $societyInfo->phone ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Email:</label>
            <div class="col-sm-9 pt-2">{{ $societyInfo->email ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Established Date:</label>
            <div class="col-sm-9 pt-2">
                {{ $societyInfo->established_date ? \Carbon\Carbon::parse($societyInfo->established_date)->format('F d, Y') : '-' }}
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Description:</label>
            <div class="col-sm-9 pt-2">{{ $societyInfo->description ?? '-' }}</div>
        </div>
        
    </div>
@endsection
