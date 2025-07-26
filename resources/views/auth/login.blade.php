@extends('layouts.app')

@section('header', 'Login')

@section('content')
    <div class="d-flex justify-content-center mt-5">
        <div class="card shadow" style="max-width: 400px; width: 100%;">
            <div class="card-body p-4">
                <h3 class="card-title mb-4 text-center">Login</h3>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Errors:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autofocus>
                        <div class="invalid-feedback">Please enter your email.</div>
                        {{-- @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror --}}
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required>
                        <div class="invalid-feedback">Please enter your password.</div>
                        {{-- @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror --}}
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot your
                            password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
@endsection
