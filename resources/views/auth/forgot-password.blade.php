@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 400px;">
    <h3 class="mb-4">Forgot Password</h3>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
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

    <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email"
                class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autofocus>
            <div class="invalid-feedback">Please enter your email address.</div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Send Password Reset Link</button>
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
