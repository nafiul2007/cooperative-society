@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold fs-4 text-dark">
        {{ __('Profile') }}
    </h2>
@endsection

@section('content')
    <div class="card">
        <div class="d-flex justify-content-between align-items-center card-header">
            <span>Edit Profile Info</span>
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row g-4">
                    <div class="col-12 col-md-10 mx-auto">
                        <div class="card mb-4 bg-white shadow-sm">
                            @include('profile.partials.update-profile-form')
                        </div>

                        <div class="card mb-4 bg-white shadow-sm">
                            @include('profile.partials.update-email-form')
                        </div>

                        <div class="card mb-4 bg-white shadow-sm">
                            @include('profile.partials.update-password-form')
                        </div>

                        {{-- <div class="card mb-4 bg-white shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
