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
