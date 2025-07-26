@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 400px;">
    <h3 class="mb-4">Confirm Password</h3>

    <p>Please confirm your password before continuing.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}" class="needs-validation" novalidate>
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required autofocus>
            <div class="invalid-feedback">Please enter your password.</div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Confirm Password</button>
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
