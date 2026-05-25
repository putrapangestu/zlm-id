<x-guest-layout>
    @section('title', 'Create Account')

    <div class="text-center mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-[#363230]">Create an account</h1>
        <p class="text-sm text-gray-500 mt-1.5">Join ZLM.ID for exclusive deals</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="name" class="block mt-1.5 w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#DF5E1D] focus:ring-[#DF5E1D]/20 focus:bg-white transition" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="email" class="block mt-1.5 w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#DF5E1D] focus:ring-[#DF5E1D]/20 focus:bg-white transition" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password" class="block mt-1.5 w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#DF5E1D] focus:ring-[#DF5E1D]/20 focus:bg-white transition" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password_confirmation" class="block mt-1.5 w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#DF5E1D] focus:ring-[#DF5E1D]/20 focus:bg-white transition" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center py-3 bg-gradient-to-b from-[#DF5E1D] to-[#d05619] hover:from-[#d05619] hover:to-[#c45218] text-white rounded-xl font-medium shadow-sm transition-all">
            <iconify-icon icon="solar:user-plus-linear" class="text-lg"></iconify-icon>
            {{ __('Create Account') }}
        </x-primary-button>

        <p class="text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-[#DF5E1D] hover:text-[#c45218] transition">Sign In</a>
        </p>
    </form>
</x-guest-layout>