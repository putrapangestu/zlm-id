<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil & Keanggotaan Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Digital Member Card Banner --}}
            @php
                $user = auth()->user();
                $tier = strtolower($user->member_tier ?? 'bronze');
                $cardBg = match($tier) {
                    'platinum' => 'from-slate-900 via-purple-950 to-slate-900 text-white border-purple-500/30',
                    'gold' => 'from-amber-700 via-amber-900 to-yellow-900 text-white border-amber-400/40',
                    'silver' => 'from-slate-700 via-gray-800 to-zinc-900 text-white border-slate-400/30',
                    default => 'from-orange-800 via-orange-950 to-stone-900 text-white border-orange-500/30',
                };
            @endphp

            <div class="p-6 sm:p-8 bg-white shadow sm:rounded-2xl border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="max-w-md w-full">
                    <div class="relative bg-gradient-to-br {{ $cardBg }} rounded-3xl p-6 shadow-xl border overflow-hidden aspect-[1.58/1] flex flex-col justify-between">
                        <div class="absolute -right-8 -top-8 w-36 h-36 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('assets/logo.png') }}" alt="" class="h-6 w-6 object-contain brightness-0 invert">
                                <span class="font-bold tracking-tighter text-sm">ZLM<span class="text-[#DF5E1D]">.ID</span> MEMBER</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-white/20 backdrop-blur-md">
                                {{ $tier }}
                            </span>
                        </div>

                        <div class="my-auto py-2">
                            <span class="font-mono text-sm tracking-widest block font-bold">{{ $user->member_number ?? 'MBR-000000' }}</span>
                            <span class="text-xs text-white/80 block mt-1 font-semibold uppercase">{{ $user->name }}</span>
                        </div>

                        <div class="flex items-end justify-between pt-2 border-t border-white/10 text-xs">
                            <div>
                                <span class="text-[10px] text-white/60 block uppercase">Poin Belanja</span>
                                <span class="font-mono font-bold text-sm text-[#DF5E1D]">{{ number_format($user->member_points) }} PTS</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-white/60 block uppercase">Diskon Spesial</span>
                                <span class="font-bold text-sm">{{ $user->tier_discount_percentage }}% OFF</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1 space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-50 text-[#DF5E1D] text-xs font-bold rounded-full">
                        <iconify-icon icon="solar:crown-star-bold"></iconify-icon>
                        <span>Keuntungan Member ZLM.ID</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Kartu Member Digital Anda</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Tunjukkan nomor member ini saat berbelanja langsung di <strong>Store / Kasir POS ZLM</strong> atau nikmati diskon otomatis saat checkout online. Dapatkan poin loyalitas pada setiap transaksi!
                    </p>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="text-[10px] text-gray-400 block uppercase font-bold">Diskon Belanja</span>
                            <span class="text-sm font-bold text-emerald-600">{{ $user->tier_discount_percentage }}% Otomatis</span>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="text-[10px] text-gray-400 block uppercase font-bold">Saldo Poin</span>
                            <span class="text-sm font-bold text-[#DF5E1D]">{{ number_format($user->member_points) }} Poin</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
