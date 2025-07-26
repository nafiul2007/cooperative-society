<section class="space-y-6">
    <header>
        <h2 class="h5 fw-semibold text-dark">
            {{ __('Delete Account') }}
        </h2>

        <p class="text-muted small">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="btn btn-danger"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
            @csrf
            @method('delete')

            <h2 class="h6 fw-semibold text-dark">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="text-muted small mt-2 mb-4">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" class="form-label visually-hidden" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control w-75"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="invalid-feedback d-block mt-1" />
            </div>

            <div class="d-flex justify-content-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')" class="btn btn-secondary">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="btn btn-danger ms-2">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
