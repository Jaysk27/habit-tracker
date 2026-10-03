<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-semibold text-gray-900">{{ __('Create Account') }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('Start building better habits today') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="space-y-5">
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" class="block mt-1 w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" class="block mt-1 w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full h-11 px-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center h-11 text-base">
                {{ __('Create Account') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <span class="text-sm text-gray-500">{{ __('Already have an account?') }}</span>
            <a class="text-sm font-medium text-indigo-600 hover:text-indigo-800 ml-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Sign in') }}
            </a>
        </div>
    </form>
</x-guest-layout>