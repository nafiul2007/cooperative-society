<div class="d-flex justify-content-between align-items-center card-header">
    <span>Personal Details</span>
</div>
<div class="card-body">
    <div id="ajaxStatus"></div>

    <form id="ajaxProfileForm" class="needs-validation mt-4" novalidate>
        @csrf
        @method('PATCH')

        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" id="name" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', optional($member)->name) }}" required>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Please enter Name.</div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="mobile_number" class="col-sm-3 col-form-label">Mobile Number <span
                    class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" id="mobile_number" name="mobile_number"
                    class="form-control @error('mobile_number') is-invalid @enderror"
                    value="{{ old('mobile_number', optional($member)->mobile_number) }}" required>
                @error('mobile_number')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Please enter valid Mobile Number.</div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="nid" class="col-sm-3 col-form-label">NID <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" id="nid" name="nid"
                    class="form-control @error('nid') is-invalid @enderror"
                    value="{{ old('nid', optional($member)->nid) }}" required>
                @error('nid')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Please enter valid NID.</div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="date_of_birth" class="col-sm-3 col-form-label">Date of Birth <span
                    class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="date" id="date_of_birth" name="date_of_birth"
                    class="form-control @error('date_of_birth') is-invalid @enderror"
                    value="{{ old('date_of_birth', optional($member)->date_of_birth) }}" required>
                @error('date_of_birth')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback">Please enter valid Date of Birth.</div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="tin" class="col-sm-3 col-form-label">TIN</label>
            <div class="col-sm-9">
                <input type="text" id="tin" name="tin"
                    class="form-control @error('tin') is-invalid @enderror"
                    value="{{ old('tin', optional($member)->tin) }}">
                @error('tin')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-9">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>

@if (!$member)
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                </div>
                <div class="modal-body" id="successModalMessage">
                    <!-- Message inserted dynamically -->
                </div>
                <div class="modal-footer">
                    <button id="successModalOkBtn" type="button" class="btn btn-primary"
                        data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerText = 'Submitting...';
                        }
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ajaxProfileForm');
            const statusContainer = document.getElementById('ajaxStatus');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Bootstrap validation
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return; // ⛔ STOP if client-side validation fails
                }

                // Prepare UI
                statusContainer.innerHTML = '';
                const submitBtn = form.querySelector('button[type="submit"]');
                const formData = new FormData(form);
                submitBtn.disabled = true;
                submitBtn.innerText = 'Submitting...';

                // Clear previous errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback.d-block').forEach(el => el.remove());

                try {
                    const response = await fetch(`{{ route('profile.updateProfile') }}`, {
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
                            ${data.message ?? 'Profile updated successfully.'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;

                        @if (!$member)
                            const modalMessage = data.message ?? 'Profile updated successfully.';
                            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                            document.getElementById('successModalMessage').textContent = modalMessage;
                            successModal.show();
                            // Add redirect on OK
                            document.getElementById('successModalOkBtn').onclick = () => {
                                successModal.hide(); // Optional
                                window.location.href = '/profile'; // Change to your desired route
                            };
                        @endif
                    } else if (response.status === 422) {
                        // Validation errors from backend
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
