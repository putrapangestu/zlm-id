@extends('layouts.guest')

@section('title', 'Masukkan Kode OTP')

@section('content')
<div class="w-full">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-[#363230]">Verifikasi Kode OTP</h1>
        <p class="text-sm text-gray-500 mt-2">
            Kami telah mengirim kode verifikasi 6 digit ke<br>
            <strong class="text-[#363230] font-semibold">{{ $email }}</strong>
        </p>
    </div>

    @if (session('status'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs sm:text-sm text-emerald-700 flex items-center gap-2">
            <iconify-icon icon="solar:check-circle-bold" class="text-emerald-500 text-base shrink-0"></iconify-icon>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-xs sm:text-sm text-rose-600 flex items-center gap-2">
            <iconify-icon icon="solar:danger-circle-bold" class="text-rose-500 text-base shrink-0"></iconify-icon>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form id="otp-form" method="POST" action="{{ route('otp.verify.store') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="otp" id="hidden-otp" value="">

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider text-center mb-3">
                Masukkan 6 Digit OTP
            </label>
            <div class="flex justify-between items-center gap-2 max-w-[320px] mx-auto" id="otp-inputs-container">
                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit w-11 h-13 sm:w-12 sm:h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl outline-none focus:border-[#DF5E1D] focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all text-[#363230]" autofocus autocomplete="off">
                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit w-11 h-13 sm:w-12 sm:h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl outline-none focus:border-[#DF5E1D] focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all text-[#363230]" autocomplete="off">
                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit w-11 h-13 sm:w-12 sm:h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl outline-none focus:border-[#DF5E1D] focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all text-[#363230]" autocomplete="off">
                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit w-11 h-13 sm:w-12 sm:h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl outline-none focus:border-[#DF5E1D] focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all text-[#363230]" autocomplete="off">
                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit w-11 h-13 sm:w-12 sm:h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl outline-none focus:border-[#DF5E1D] focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all text-[#363230]" autocomplete="off">
                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-digit w-11 h-13 sm:w-12 sm:h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl outline-none focus:border-[#DF5E1D] focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all text-[#363230]" autocomplete="off">
            </div>
        </div>

        <button type="submit" id="btn-submit-otp" class="w-full py-3.5 px-4 rounded-xl bg-[#363230] text-white text-sm font-semibold hover:bg-[#DF5E1D] shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
            <span>Verifikasi & Lanjutkan</span>
            <iconify-icon icon="solar:arrow-right-linear" class="text-lg"></iconify-icon>
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-100 text-center space-y-4">
        <p class="text-sm text-gray-500">
            Tidak menerima kode?
            <form method="POST" action="{{ route('otp.resend') }}" class="inline">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="type" value="{{ $type }}">
                <button type="submit" id="resend-btn" class="text-[#DF5E1D] hover:text-[#c45218] font-semibold text-sm transition-colors ml-1 underline">
                    Kirim Ulang OTP
                </button>
            </form>
        </p>

        <div>
            <a href="{{ route('otp.request') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-[#363230] transition-colors">
                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
                <span>Gunakan email lain</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs = Array.from(document.querySelectorAll('.otp-digit'));
    const hiddenOtp = document.getElementById('hidden-otp');
    const form = document.getElementById('otp-form');

    function updateHiddenValue() {
        const fullOtp = inputs.map(input => input.value).join('');
        hiddenOtp.value = fullOtp;
        return fullOtp;
    }

    inputs.forEach((input, index) => {
        // Only allow single numeric digit
        input.addEventListener('input', function (e) {
            const val = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = val ? val[val.length - 1] : '';

            if (e.target.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            const currentOtp = updateHiddenValue();
            if (currentOtp.length === 6) {
                form.submit();
            }
        });

        // Handle Backspace and Arrow keys
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace') {
                if (!e.target.value && index > 0) {
                    inputs[index - 1].focus();
                    inputs[index - 1].value = '';
                } else {
                    e.target.value = '';
                }
                updateHiddenValue();
            } else if (e.key === 'ArrowLeft' && index > 0) {
                inputs[index - 1].focus();
            } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        // Handle paste across boxes
        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            const cleanDigits = pasted.replace(/[^0-9]/g, '').slice(0, 6);

            for (let i = 0; i < cleanDigits.length; i++) {
                if (inputs[i]) {
                    inputs[i].value = cleanDigits[i];
                }
            }

            const nextIndex = Math.min(cleanDigits.length, inputs.length - 1);
            inputs[nextIndex].focus();

            const fullOtp = updateHiddenValue();
            if (fullOtp.length === 6) {
                form.submit();
            }
        });
    });

    form.addEventListener('submit', function (e) {
        const val = updateHiddenValue();
        if (val.length !== 6) {
            e.preventDefault();
            if (typeof window.showToast === 'function') {
                window.showToast('Silakan masukkan 6 digit kode OTP secara lengkap.', 'warning');
            }
            inputs.find(i => !i.value)?.focus();
        }
    });

    // Auto focus first input
    if (inputs[0]) {
        inputs[0].focus();
    }
});
</script>
@endpush
@endsection
