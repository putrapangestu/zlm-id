@php $compareCount = count(session('compare', [])); @endphp

<div id="floating-compare"
     class="fixed bottom-6 right-6 z-50 {{ $compareCount > 0 ? '' : 'hidden' }} transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-200 p-4">
        <div class="flex items-center gap-3">
            <div class="relative">
                <iconify-icon icon="solar:scale-linear" class="text-2xl text-[#363230]"></iconify-icon>
                <span id="compare-badge"
                      class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                    {{ $compareCount }}
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-[#363230]">Compare <span id="compare-count">{{ $compareCount }}</span>/3</p>
                <div class="flex gap-2 mt-1">
                    <a href="{{ route('landing.compare') }}"
                       class="text-xs font-medium text-[#DF5E1D] hover:text-[#c45218] transition-colors">
                        View
                    </a>
                    <button onclick="clearCompare()"
                            class="text-xs font-medium text-gray-400 hover:text-red-500 transition-colors">
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
