<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-2 text-sm text-gray-500">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data you wish to retain before continuing.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-xl font-semibold text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-3 text-sm text-gray-500">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="{{ __('Enter your password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="px-4 py-2">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>