<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-semibold text-gray-900">{{ __('Reset Password') }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('Forgot your password? We\'ll send you a reset link.') }}</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center h-11 text-base">
                {{ __('Send Reset Link') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <span class="text-sm text-gray-500">{{ __('Remember your password?') }}</span>
            <a class="text-sm font-medium text-indigo-600 hover:text-indigo-800 ml-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Sign in') }}
            </a>
        </div>
    </form>
</x-guest-layout>