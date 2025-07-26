<div class="d-flex justify-content-between align-items-center card-header">
    <span>Update Password</span>
</div>
<div class="card-body">
    <div id="ajaxPasswordStatus"></div>

    <form id="ajaxPasswordForm" method="POST" action="{{ route('password.update') }}" class="needs-validation mt-4"
        novalidate>
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <label for="current_password" class="col-sm-3 col-form-label">Current Password <span
                    class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="password" id="current_password" name="current_password"
                    class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password"
                    required>
                @error('current_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Please enter your current password.</div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-sm-3 col-form-label">New Password <span
                    class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Please enter a new password.</div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="password_confirmation" class="col-sm-3 col-form-label">Confirm Password <span
                    class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    autocomplete="new-password" required>
                @error('password_confirmation')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Please confirm your new password.</div>
            </div>
        </div>


        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-9">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ajaxPasswordForm');
            const statusContainer = document.getElementById('ajaxPasswordStatus');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const currentInput = form.querySelector('#current_password');
                const passwordInput = form.querySelector('#password');
                const confirmInput = form.querySelector('#password_confirmation');

                // Clear previous validation
                [currentInput, passwordInput, confirmInput].forEach(input => {
                    input.setCustomValidity('');
                    input.classList.remove('is-invalid');
                });
                form.querySelectorAll('.invalid-feedback.d-block').forEach(el => el.remove());

                // Basic browser validation
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                const submitBtn = form.querySelector('button[type="submit"]');
                let isValid = true;
                // Custom Rule 1: New password and confirm password must match
                if (passwordInput.value !== confirmInput.value) {
                    confirmInput.setCustomValidity('Passwords do not match.');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback d-block';
                    feedback.innerText = 'Passwords do not match.';
                    confirmInput.insertAdjacentElement('afterend', feedback);
                    form.classList.add('was-validated');
                    isValid = false;
                }

                // Custom Rule 2: New password cannot be the same as current password
                if (currentInput.value === passwordInput.value) {
                    passwordInput.setCustomValidity(
                        'New password cannot be the same as current password.');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback d-block';
                    feedback.innerText = 'New password cannot be the same as current password.';
                    passwordInput.insertAdjacentElement('afterend', feedback);
                    form.classList.add('was-validated');
                    isValid = false;
                }
                if (!isValid) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Submit';
                    return;
                }

                // All checks passed — prepare to send
                submitBtn.disabled = true;
                submitBtn.innerText = 'Submitting...';

                statusContainer.innerHTML = '';
                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')
                                .value,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        statusContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${data.message ?? 'Password updated successfully.'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                        form.reset();
                        form.classList.remove('was-validated');
                    } else if (response.status === 422) {
                        const errors = data.errors;
                        for (const [key, messages] of Object.entries(errors)) {
                            const input = document.getElementById(key);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback d-block';
                                feedback.innerText = messages.join(', ');
                                input.insertAdjacentElement('afterend', feedback);
                            }
                        }
                        statusContainer.innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            Please correct the errors below.
                        </div>`;
                    } else {
                        statusContainer.innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            Something went wrong. Please try again.
                        </div>`;
                    }

                } catch (error) {
                    console.error(error);
                    statusContainer.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        Server error. Please try again later.
                    </div>`;
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Submit';
                }
            });
        });
    </script>
@endpush
