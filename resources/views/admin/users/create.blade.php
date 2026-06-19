@extends('layouts.admin')

@section('title', 'Create User - ZLM.ID Admin')
@section('heading', 'Create User')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('admin.users.index') }}" class="hover:text-[#DF5E1D] transition-colors">Users</a>
        <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
        <span class="text-[#363230] font-medium">Create</span>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8">
        <h2 class="text-lg font-semibold text-[#363230] mb-6">Create New User</h2>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-[#363230] mb-1.5">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all @error('name') border-red-300 @enderror"
                    placeholder="Full name">
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-[#363230] mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all @error('email') border-red-300 @enderror"
                    placeholder="email@example.com">
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-[#363230] mb-1.5">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all @error('password') border-red-300 @enderror"
                    placeholder="Min. 8 characters">
                @error('password')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-[#363230] mb-1.5">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                    placeholder="Repeat password">
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-sm font-medium text-[#363230] mb-1.5">Role</label>
                <div class="relative">
                    <select id="role" name="role" required
                        class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all cursor-pointer @error('role') border-red-300 @enderror">
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected(old('role') === $role->name)>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" style="stroke-width: 1.5;"></iconify-icon>
                </div>
                @error('role')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-medium text-white bg-[#DF5E1D] hover:bg-[#c45218] transition-colors shadow-sm">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
