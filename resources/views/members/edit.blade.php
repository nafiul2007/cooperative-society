@extends('layouts.app')

@section('header', 'Edit Member')

@section('content')
    <div class="card">
        <div class="d-flex justify-content-between align-items-center card-header">
            <span>Edit Member Info</span>
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

            <form action="{{ route('members.update', $member) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <label for="name" class="col-sm-3 col-form-label">Name <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $member->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="mobile_number" class="col-sm-3 col-form-label">Mobile Number <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" id="mobile_number" name="mobile_number"
                            class="form-control @error('mobile_number') is-invalid @enderror"
                            value="{{ old('mobile_number', $member->mobile_number) }}" required>
                        @error('mobile_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="nid" class="col-sm-3 col-form-label">NID <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" id="nid" name="nid"
                            class="form-control @error('nid') is-invalid @enderror" value="{{ old('nid', $member->nid) }}"
                            maxlength="25" required>
                        @error('nid')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="date_of_birth" class="col-sm-3 col-form-label">Date of Birth <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" id="date_of_birth" name="date_of_birth"
                            class="form-control @error('date_of_birth') is-invalid @enderror"
                            value="{{ old('date_of_birth', $member->date_of_birth) }}" required>
                        @error('date_of_birth')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="tin" class="col-sm-3 col-form-label">TIN</label>
                    <div class="col-sm-9">
                        <input type="text" id="tin" name="tin"
                            class="form-control @error('tin') is-invalid @enderror" value="{{ old('tin', $member->tin) }}"
                            maxlength="30">
                        @error('tin')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="business_share" class="col-sm-3 col-form-label">Business Share</label>
                    <div class="col-sm-9">
                        <input type="number" step="0.001" id="business_share" name="business_share"
                            class="form-control @error('business_share') is-invalid @enderror"
                            value="{{ old('business_share', $member->business_share) }}" min="0">
                        @error('business_share')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="offset-sm-3 col-sm-9">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            'use strict'

            const forms = document.querySelectorAll('.needs-validation')

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    } else {
                        const submitBtn = form.querySelector('button[type="submit"]')
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerText = 'Submitting...';
                        }
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
@endpush
