<footer class="bg-[#1f1d1b] pt-16 pb-8 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- 4 Column Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8 mb-12">

            {{-- Column 1: Brand --}}
            <div>
                <a href="{{ route('landing.home') }}" class="inline-block mb-4">
                    @if(config('settings.store_logo'))
                        <img src="{{ asset('storage/' . config('settings.store_logo')) }}" alt="{{ config('settings.store_name', 'ZLM.ID') }}" class="h-8">
                    @else
                        <h4 class="text-white font-bold text-xl tracking-tight">ZLM.ID</h4>
                    @endif
                </a>
                <p class="text-sm text-gray-400 leading-relaxed">{{ config('settings.store_description', 'Premium laptop store — engineered excellence for professionals, creators, and gamers.') }}</p>
            </div>

            {{-- Column 2: Links --}}
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Links</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('landing.search') }}" class="text-sm text-gray-400 hover:text-[#DF5E1D] transition-colors flex items-center gap-2">
                            <iconify-icon icon="solar:arrow-right-minimalistic-linear" class="text-xs"></iconify-icon>
                            Katalog
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.smart-search') }}" class="text-sm text-gray-400 hover:text-[#DF5E1D] transition-colors flex items-center gap-2">
                            <iconify-icon icon="solar:arrow-right-minimalistic-linear" class="text-xs"></iconify-icon>
                            Smart Search
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.compare') }}" class="text-sm text-gray-400 hover:text-[#DF5E1D] transition-colors flex items-center gap-2">
                            <iconify-icon icon="solar:arrow-right-minimalistic-linear" class="text-xs"></iconify-icon>
                            Bandingkan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.testimonials') }}" class="text-sm text-gray-400 hover:text-[#DF5E1D] transition-colors flex items-center gap-2">
                            <iconify-icon icon="solar:arrow-right-minimalistic-linear" class="text-xs"></iconify-icon>
                            Testimoni
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.articles') }}" class="text-sm text-gray-400 hover:text-[#DF5E1D] transition-colors flex items-center gap-2">
                            <iconify-icon icon="solar:arrow-right-minimalistic-linear" class="text-xs"></iconify-icon>
                            Artikel
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 3: Contact --}}
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Contact</h4>
                <ul class="space-y-3">
                    @if(config('settings.store_email'))
                        <li class="flex items-center gap-3 text-sm text-gray-400">
                            <iconify-icon icon="solar:letter-linear" class="text-[#DF5E1D]"></iconify-icon>
                            {{ config('settings.store_email') }}
                        </li>
                    @endif
                    @if(config('settings.store_phone'))
                        <li class="flex items-center gap-3 text-sm text-gray-400">
                            <iconify-icon icon="solar:phone-linear" class="text-[#DF5E1D]"></iconify-icon>
                            {{ config('settings.store_phone') }}
                        </li>
                    @endif
                    @if(config('settings.store_whatsapp'))
                        <li>
                            <a href="https://wa.me/{{ ltrim(preg_replace('/[^0-9]/', '', config('settings.store_whatsapp')), '0') }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 text-sm text-gray-400 hover:text-[#25D366] transition-colors">
                                <iconify-icon icon="solar:phone-calling-linear" class="text-[#DF5E1D]"></iconify-icon>
                                WhatsApp
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Column 4: Social Media --}}
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Ikuti Kami</h4>
                <div class="flex gap-3 flex-wrap">
                    @if(config('settings.social_instagram'))
                        <a href="{{ config('settings.social_instagram') }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-[#E4405F] hover:text-white hover:border-[#E4405F] hover:scale-110 transition-all duration-300" aria-label="Instagram">
                            <iconify-icon icon="solar:instagram-linear" class="text-lg"></iconify-icon>
                        </a>
                    @endif
                    @if(config('settings.social_facebook'))
                        <a href="{{ config('settings.social_facebook') }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-[#1877F2] hover:text-white hover:border-[#1877F2] hover:scale-110 transition-all duration-300" aria-label="Facebook">
                            <iconify-icon icon="solar:facebook-linear" class="text-lg"></iconify-icon>
                        </a>
                    @endif
                    @if(config('settings.social_tiktok'))
                        <a href="{{ config('settings.social_tiktok') }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-black hover:text-white hover:border-black hover:scale-110 transition-all duration-300" aria-label="TikTok">
                            <iconify-icon icon="solar:tiktok-linear" class="text-lg"></iconify-icon>
                        </a>
                    @endif
                    @if(config('settings.social_youtube'))
                        <a href="{{ config('settings.social_youtube') }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-[#FF0000] hover:text-white hover:border-[#FF0000] hover:scale-110 transition-all duration-300" aria-label="YouTube">
                            <iconify-icon icon="solar:youtube-linear" class="text-lg"></iconify-icon>
                        </a>
                    @endif
                    @if(config('settings.store_whatsapp'))
                        <a href="https://wa.me/{{ ltrim(preg_replace('/[^0-9]/', '', config('settings.store_whatsapp')), '0') }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-[#25D366] hover:text-white hover:border-[#25D366] hover:scale-110 transition-all duration-300" aria-label="WhatsApp">
                            <iconify-icon icon="solar:phone-calling-linear" class="text-lg"></iconify-icon>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Address Bar --}}
        @if(config('settings.store_address') || config('settings.store_opening_hours'))
            <div class="bg-white/5 rounded-xl p-4 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-gray-400">
                    @if(config('settings.store_address'))
                        <span class="flex items-center gap-2">
                            <iconify-icon icon="solar:map-point-linear" class="text-[#DF5E1D] shrink-0"></iconify-icon>
                            {{ config('settings.store_address') }}
                        </span>
                    @endif
                    @if(config('settings.store_opening_hours'))
                        <span class="flex items-center gap-2">
                            <iconify-icon icon="solar:clock-circle-linear" class="text-[#DF5E1D] shrink-0"></iconify-icon>
                            {{ config('settings.store_opening_hours') }}
                        </span>
                    @endif
                </div>
            </div>
        @endif

        {{-- Google Maps --}}
        @if(config('settings.store_google_maps'))
            <div class="mb-8 rounded-xl overflow-hidden border border-white/10">
                {!! config('settings.store_google_maps') !!}
            </div>
        @endif

        {{-- Copyright --}}
        <div class="pt-8 border-t border-white/5 text-center">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} ZLM.ID. All rights reserved.</p>
        </div>
    </div>
</footer>
