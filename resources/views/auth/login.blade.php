<x-guest-layout>
    @section('title', 'Sign In')

    <div class="text-center mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-[#363230]">Welcome back</h1>
        <p class="text-sm text-gray-500 mt-1.5">Sign in to access your account</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="email" class="block mt-1.5 w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#DF5E1D] focus:ring-[#DF5E1D]/20 focus:bg-white transition" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-[#DF5E1D] hover:text-[#c45218] transition" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block mt-1.5 w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#DF5E1D] focus:ring-[#DF5E1D]/20 focus:bg-white transition" type="password" name="password" required autocomplete="current-password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#DF5E1D] focus:ring-[#DF5E1D]/30 shadow-sm" name="remember">
                <span class="text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <x-primary-button class="w-full justify-center py-3 bg-gradient-to-b from-[#DF5E1D] to-[#d05619] hover:from-[#d05619] hover:to-[#c45218] text-white rounded-xl font-medium shadow-sm transition-all">
            <iconify-icon icon="solar:login-3-linear" class="text-lg"></iconify-icon>
            {{ __('Sign In') }}
        </x-primary-button>

        <p class="text-center text-sm text-gray-500">
            Don&rsquo;t have an account?
            <a href="{{ route('register') }}" class="font-medium text-[#DF5E1D] hover:text-[#c45218] transition">Register</a>
        </p>
    </form>
</x-guest-layout>