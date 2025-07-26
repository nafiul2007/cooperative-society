<div class="d-flex justify-content-between align-items-center card-header">
    <span>Change Email</span>
</div>

<div class="card-body">
    {{-- AJAX Status Message --}}
    <div id="ajaxEmailStatus"></div>

    @if (session('status') === 'email-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Your email has been successfully updated.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.focus();
            }
        });
    </script>
    @endif

    {{-- Email Update Form (AJAX Submission) --}}
    <form id="ajaxEmailForm" class="needs-validation mt-4" novalidate>
        @csrf
        @method('PATCH')

        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Current Email </label>
            <div class="col-sm-9 pt-2">
                <span class="text-muted fw-semibold">{{ $user->email }}</span>
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-sm-3 col-form-label">New Email <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="email" id="email" name="email"
                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required
                    autocomplete="username">
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-9">
                <button type="submit" class="btn btn-primary">Send Verification Link</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        (() => {
            'use strict';

            // Bootstrap client-side validation
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            // AJAX form submission
            const form = document.getElementById('ajaxEmailForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    return;
                }

                const formData = new FormData(form);
                const token = document.querySelector('input[name="_token"]').value;
                let cooldownSeconds = 0; // store cooldown seconds here
                fetch("{{ route('profile.requestEmailChange') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async response => {
                        const data = await response.json();

                        if (response.ok) {
                            cooldownSeconds = data.cooldown || 0;
                            // Success: show success alert
                            document.getElementById('ajaxEmailStatus').innerHTML = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${data.message || 'Verification link sent to your new email address.'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;

                            form.reset();
                            form.classList.remove('was-validated');

                        } else {
                            cooldownSeconds = data.cooldown || 0;
                            // Validation or server error
                            let message = data.message || 'Something went wrong.';
                            let errors = data.errors || {};

                            let errorHtml = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Oops! Please fix the errors below:</strong><ul class="mb-0 mt-1">`;

                            if (Object.keys(errors).length > 0) {
                                for (let key in errors) {
                                    errors[key].forEach(msg => {
                                        errorHtml += `<li>${msg}</li>`;
                                    });
                                }
                            } else {
                                errorHtml += `<li>${message}</li>`;
                            }

                            errorHtml += `</ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;

                            document.getElementById('ajaxEmailStatus').innerHTML = errorHtml;
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        document.getElementById('ajaxEmailStatus').innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Unexpected error occurred. Please try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    })
                    .finally(() => {
                        startCountdown(cooldownSeconds);
                    });
            });
        })();

        function startCountdown(seconds) {
            const submitBtn = document.getElementById('ajaxEmailForm').querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            let remaining = seconds;

            submitBtn.innerHTML = `Resend available in ${remaining}s`;

            const interval = setInterval(() => {
                remaining--;
                if (remaining > 0) {
                    submitBtn.innerHTML = `Resend available in ${remaining}s`;
                } else {
                    clearInterval(interval);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Send Verification Link';
                }
            }, 1000);
        }
    </script>
@endpush
