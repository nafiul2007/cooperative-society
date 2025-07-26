@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 400px;">
    <h3 class="mb-4">Reset Password</h3>

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

    <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
        @csrf
        @method('PUT')  <!-- This spoofs PUT method -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email"
                class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email', $request->email) }}" required autofocus>
            <div class="invalid-feedback">Please enter your email address.</div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" type="password"
                class="form-control @error('password') is-invalid @enderror"
                name="password" required>
            <div class="invalid-feedback">Please enter a new password.</div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input id="password_confirmation" type="password"
                class="form-control"
                name="password_confirmation" required>
            <div class="invalid-feedback">Please confirm your new password.</div>
        </div>

        <button type="submit" class="btn btn-success w-100">Reset Password</button>
    </form>
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
