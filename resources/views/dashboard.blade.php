@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    Dashboard
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center card-header">
        <span>Welcome</span>
    </div>
    @if (auth()->user()->email_verified_at === null)
        <div class="alert alert-warning">
            Your email is not verified.
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                    Click here to resend verification email.
                </button>
            </form>
        </div>
    @endif
    <div class="card-body" style="text-align: center">
        <x-application-logo class="me-3" style="height: auto; width: 50%;" />
    </div>
@endsection
