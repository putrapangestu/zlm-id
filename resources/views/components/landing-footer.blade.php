<footer class="bg-[#2a2725] pt-12 pb-8 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm">ZLM.ID</h4>
                <p class="text-xs text-gray-500 leading-relaxed">Premium laptop store — engineered excellence for professionals, creators, and gamers.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm">Links</h4>
                <ul class="space-y-2 text-xs text-gray-500">
                    <li><a href="{{ route('landing.search') }}" class="hover:text-[#DF5E1D] transition-colors">Katalog</a></li>
                    <li><a href="{{ route('landing.articles') }}" class="hover:text-[#DF5E1D] transition-colors">Artikel</a></li>
                    <li><a href="{{ route('landing.compare') }}" class="hover:text-[#DF5E1D] transition-colors">Bandingkan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm">Contact</h4>
                <ul class="space-y-2 text-xs text-gray-500">
                    <li class="flex items-center gap-2">
                        <iconify-icon icon="solar:letter-linear" class="text-gray-400"></iconify-icon>
                        support@zlm.id
                    </li>
                    <li class="flex items-center gap-2">
                        <iconify-icon icon="solar:phone-linear" class="text-gray-400"></iconify-icon>
                        +62 123 4567 8910
                    </li>
                </ul>
            </div>
        </div>
        <div class="pt-8 border-t border-white/5 text-center">
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} ZLM.ID. All rights reserved.</p>
        </div>
    </div>
</footer>
