<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-semibold text-gray-900">{{ __('Welcome Back') }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('Sign in to your account') }}</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="space-y-5">
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center h-11 text-base">
                {{ __('Sign In') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <span class="text-sm text-gray-500">{{ __("Don't have an account?") }}</span>
            @if (Route::has('register'))
                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-800 ml-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                    {{ __('Create one') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>