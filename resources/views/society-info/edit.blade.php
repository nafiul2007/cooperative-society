@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center card-header">
        <span>Society Info</span>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('society-info.update') }}">
            @csrf
            @method('POST')
            <div class="row mb-3">
                <label for="name" class="col-sm-3 col-form-label">Society Name <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" id="name" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $societyInfo->name ?? '') }}" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="registration_no" class="col-sm-3 col-form-label">Registration Number <span
                        class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" id="registration_no" name="registration_no"
                        class="form-control @error('registration_no') is-invalid @enderror"
                        value="{{ old('registration_no', $societyInfo->registration_no ?? '') }}" required>
                    @error('registration_no')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="address" class="col-sm-3 col-form-label">Address <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                        required>{{ old('address', $societyInfo->address ?? '') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="phone" class="col-sm-3 col-form-label">Phone <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="text" id="phone" name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone', $societyInfo->phone ?? '') }}" required>
                    @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $societyInfo->email ?? '') }}" required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="established_date" class="col-sm-3 col-form-label">Established Date</label>
                <div class="col-sm-9">
                    <input type="date" id="established_date" name="established_date"
                        class="form-control @error('established_date') is-invalid @enderror"
                        value="{{ old('established_date', $societyInfo->established_date ?? '') }}">
                    @error('established_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="description" class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-9">
                    <textarea id="description" name="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $societyInfo->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-9">
                    <button type="submit" class="btn btn-primary save-action">Save Information</button>
                </div>
            </div>
        </form>
    </div>
@endsection
