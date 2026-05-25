@extends('layouts.guest')

@section('title', 'Verifikasi OTP')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-8">
            <div class="text-center mb-8">
                <a href="/" class="inline-block text-2xl font-bold text-[#DF5E1D] tracking-wide mb-6">ZLM.ID</a>
                <h1 class="text-xl font-semibold text-[#363230]">Verifikasi Akun</h1>
                <p class="text-sm text-gray-500 mt-1">Masukkan email Anda untuk menerima kode OTP.</p>
            </div>

            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('otp.request.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div>
                    <label for="email" class="block text-sm font-medium text-[#363230] mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] outline-none transition-all"
                           placeholder="email@example.com" required autofocus>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-[#363230] text-white text-sm font-medium hover:bg-[#DF5E1D] transition-all duration-300">
                    Kirim Kode OTP
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya kode?
                <a href="{{ route('otp.verify') }}" class="text-[#DF5E1D] hover:text-[#c45218] font-medium transition-colors">
                    Masukkan kode
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
