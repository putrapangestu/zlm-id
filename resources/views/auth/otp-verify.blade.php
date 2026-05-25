@extends('layouts.guest')

@section('title', 'Masukkan Kode OTP')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-8">
            <div class="text-center mb-8">
                <a href="/" class="inline-block text-2xl font-bold text-[#DF5E1D] tracking-wide mb-6">ZLM.ID</a>
                <h1 class="text-xl font-semibold text-[#363230]">Masukkan Kode OTP</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kami telah mengirim kode ke
                    <strong class="text-[#363230]">{{ $email }}</strong>
                </p>
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

            <form id="otp-form" method="POST" action="{{ route('otp.verify.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="flex justify-center gap-2" x-data="otpInput()">
                    <template x-for="(_, index) in 6" :key="index">
                        <input type="text" inputmode="numeric" maxlength="1"
                               x-ref="input$ref(index)"
                               @input="handleInput(index, $event)"
                               @keydown.backspace="handleBackspace(index, $event)"
                               @paste="handlePaste($event)"
                               class="w-12 h-14 text-center text-xl font-bold border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all"
                               required>
                    </template>
                    <input type="hidden" name="otp" x-ref="hiddenOtp">
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-[#363230] text-white text-sm font-medium hover:bg-[#DF5E1D] transition-all duration-300">
                    Verifikasi
                </button>
            </form>

            <div class="text-center mt-6 space-y-3">
                <p class="text-sm text-gray-500">
                    Tidak menerima kode?
                    <form method="POST" action="{{ route('otp.resend') }}" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button type="submit" id="resend-btn"
                                class="text-[#DF5E1D] hover:text-[#c45218] font-medium transition-colors">
                            Kirim Ulang
                        </button>
                    </form>
                </p>
                <p>
                    <a href="{{ route('otp.request') }}" class="text-sm text-gray-400 hover:text-[#363230] transition-colors">
                        &larr; Gunakan email lain
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function otpInput() {
        return {
            init() {
                this.$nextTick(() => {
                    const first = this.$refs['input0'];
                    if (first) first.focus();
                });
            },
            handleInput(index, event) {
                const input = event.target;
                if (input.value.length === 1) {
                    const next = this.$refs['input' + (index + 1)];
                    if (next) next.focus();
                }
                this.updateHidden();
            },
            handleBackspace(index, event) {
                if (event.target.value === '' && index > 0) {
                    const prev = this.$refs['input' + (index - 1)];
                    if (prev) { prev.focus(); prev.value = ''; }
                }
                this.updateHidden();
            },
            handlePaste(event) {
                event.preventDefault();
                const data = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                for (let i = 0; i < data.length; i++) {
                    const input = this.$refs['input' + i];
                    if (input) input.value = data[i];
                }
                const next = this.$refs['input' + Math.min(data.length, 5)];
                if (next) next.focus();
                this.updateHidden();
            },
            updateHidden() {
                let value = '';
                for (let i = 0; i < 6; i++) {
                    const input = this.$refs['input' + i];
                    if (input) value += input.value;
                }
                this.$refs.hiddenOtp.value = value;
            }
        }
    }
</script>
@endpush
@endsection
