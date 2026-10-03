<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Change Password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Use a strong, unique password to keep your account secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm New Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button class="px-6">{{ __('Update Password') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-500">
                    {{ __('Saved!') }}
                </p>
            @endif
        </div>
    </form>
</section>