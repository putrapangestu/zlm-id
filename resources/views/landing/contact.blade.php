@extends('layouts.landing')

@section('title', 'Kontak Kami & Lokasi Toko — ZLM.ID')

@section('content')
<div class="bg-[#FAFAFA] min-h-screen py-12">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        {{-- Hero Title --}}
        <div class="text-center max-w-2xl mx-auto">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 text-[#DF5E1D] text-xs font-bold uppercase tracking-wider mb-3">
                <iconify-icon icon="solar:map-point-linear"></iconify-icon>
                Lokasi & Hubungi Kami
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#363230] tracking-tight">Kunjungi Store Kami atau Hubungi Kami</h1>
            <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                Butuh konsultasi laptop terbaik, garansi, atau ingin cek unit langsung di toko? Tim ZLM.ID siap melayani Anda.
            </p>
        </div>

        {{-- Info Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- WhatsApp Card --}}
            <a href="https://wa.me/{{ ltrim(preg_replace('/[^0-9]/', '', $storeInfo['whatsapp']), '0') }}?text=Halo%20Admin%20ZLM%2CID%2C%20saya%20ingin%20bertanya%20mengenai%20unit%20laptop" target="_blank"
                class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm hover:border-[#25D366] hover:shadow-md transition-all group block">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#25D366] flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <iconify-icon icon="solar:phone-calling-linear"></iconify-icon>
                </div>
                <h3 class="font-bold text-[#363230] mt-4 text-base">WhatsApp Kami</h3>
                <p class="text-xs text-gray-400 mt-1">Respon cepat & ramah</p>
                <p class="text-sm font-semibold text-[#25D366] mt-3 font-mono">{{ $storeInfo['whatsapp'] }}</p>
            </a>

            {{-- Phone & Email Card --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#DF5E1D] flex items-center justify-center text-2xl">
                    <iconify-icon icon="solar:letter-linear"></iconify-icon>
                </div>
                <h3 class="font-bold text-[#363230] mt-4 text-base">Email & Telepon</h3>
                <p class="text-xs text-gray-400 mt-1">Bantuan teknis & kemitraan</p>
                <p class="text-xs font-semibold text-[#363230] mt-3 truncate">{{ $storeInfo['email'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $storeInfo['phone'] }}</p>
            </div>

            {{-- Opening Hours Card --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                    <iconify-icon icon="solar:clock-circle-linear"></iconify-icon>
                </div>
                <h3 class="font-bold text-[#363230] mt-4 text-base">Jam Operasional</h3>
                <p class="text-xs text-gray-400 mt-1">Store offline & online</p>
                <p class="text-xs font-semibold text-[#363230] mt-3">{{ $storeInfo['hours'] }}</p>
            </div>

            {{-- Social Media Card --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl">
                    <iconify-icon icon="solar:share-circle-linear"></iconify-icon>
                </div>
                <h3 class="font-bold text-[#363230] mt-4 text-base">Media Sosial</h3>
                <p class="text-xs text-gray-400 mt-1">Update promo & review</p>
                <div class="flex items-center gap-2.5 mt-3">
                    @if($storeInfo['instagram'])
                        <a href="{{ $storeInfo['instagram'] }}" target="_blank" class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center hover:bg-pink-600 hover:text-white transition">
                            <iconify-icon icon="solar:instagram-linear" class="text-base"></iconify-icon>
                        </a>
                    @endif
                    @if($storeInfo['tiktok'])
                        <a href="{{ $storeInfo['tiktok'] }}" target="_blank" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-900 flex items-center justify-center hover:bg-black hover:text-white transition">
                            <iconify-icon icon="solar:play-circle-linear" class="text-base"></iconify-icon>
                        </a>
                    @endif
                    @if($storeInfo['youtube'])
                        <a href="{{ $storeInfo['youtube'] }}" target="_blank" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition">
                            <iconify-icon icon="solar:video-frame-linear" class="text-base"></iconify-icon>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Map & Form Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Google Map & Address (7 Cols) --}}
            <div class="lg:col-span-7 bg-white rounded-3xl border border-gray-200/60 shadow-sm p-6 sm:p-8 space-y-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-[#363230] flex items-center gap-2">
                            <iconify-icon icon="solar:shop-2-bold" class="text-[#DF5E1D]"></iconify-icon>
                            Store Offline ZLM.ID
                        </h2>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $storeInfo['address'] }}</p>
                    </div>

                    <a href="https://maps.google.com/?q={{ urlencode($storeInfo['address']) }}" target="_blank" class="px-4 py-2 bg-[#DF5E1D] text-white hover:bg-[#c45218] rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5 shrink-0 whitespace-nowrap">
                        <iconify-icon icon="solar:routing-2-linear" class="text-base"></iconify-icon>
                        <span>Petunjuk Arah</span>
                    </a>
                </div>

                {{-- Interactive Map Embed --}}
                <div class="rounded-2xl overflow-hidden border border-gray-200 aspect-[16/10] bg-gray-100 shadow-inner">
                    @if(!empty($storeInfo['maps']))
                        {!! $storeInfo['maps'] !!}
                    @else
                        <iframe
                            src="https://maps.google.com/maps?q={{ urlencode($storeInfo['address']) }}&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    @endif
                </div>

                <div class="p-4 bg-orange-50/60 rounded-2xl border border-orange-200/60 text-xs text-orange-900 flex items-center gap-3">
                    <iconify-icon icon="solar:shield-check-bold" class="text-2xl text-[#DF5E1D] shrink-0"></iconify-icon>
                    <p>Semua unit laptop di toko kami siap dicek langsung di tempat. Tim teknisi kami akan mendampingi Anda untuk pengujian benchmark, kondisi fisik, dan kelengkapan garansi.</p>
                </div>
            </div>

            {{-- Contact Message Form (5 Cols) --}}
            <div class="lg:col-span-5 bg-white rounded-3xl border border-gray-200/60 shadow-sm p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-[#363230]">Kirim Pesan</h2>
                    <p class="text-xs text-gray-500 mt-1">Sampaikan pertanyaan atau kebutuhan Anda melalui formulir di bawah ini.</p>
                </div>

                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-700 flex items-center gap-2">
                        <iconify-icon icon="solar:check-circle-bold" class="text-emerald-500 text-lg shrink-0"></iconify-icon>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('landing.contact.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-bold text-[#363230] uppercase mb-1">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-[#363230] focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-4 focus:ring-[#DF5E1D]/10"
                            placeholder="Nama Anda">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-bold text-[#363230] uppercase mb-1">Email</label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-[#363230] focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-4 focus:ring-[#DF5E1D]/10"
                                placeholder="email@domain.com">
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-[#363230] uppercase mb-1">No. WhatsApp</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-[#363230] focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-4 focus:ring-[#DF5E1D]/10"
                                placeholder="08123456789">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-xs font-bold text-[#363230] uppercase mb-1">Subjek Pertanyaan</label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs text-[#363230] focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-4 focus:ring-[#DF5E1D]/10"
                            placeholder="Tanya ketersediaan stok / klaim garansi">
                    </div>

                    <div>
                        <label for="message" class="block text-xs font-bold text-[#363230] uppercase mb-1">Pesan Anda</label>
                        <textarea id="message" name="message" rows="4" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs text-[#363230] focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-4 focus:ring-[#DF5E1D]/10"
                            placeholder="Tuliskan pesan atau pertanyaan Anda secara rinci..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 bg-[#363230] hover:bg-[#DF5E1D] text-white font-bold rounded-xl text-xs transition-all shadow-md flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:plain-linear" class="text-base"></iconify-icon>
                        <span>Kirim Pesan Sekarang</span>
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
