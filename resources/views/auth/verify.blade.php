@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="mb-4">Verify Your Email Address</h3>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            A fresh verification link has been sent to your email address.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <p>
        Thanks for signing up! Before getting started, could you verify your email address by clicking the link we just emailed to you?
        If you didn’t receive the email, we will gladly send you another.
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-secondary">Logout</button>
    </form>
</div>
@endsection
