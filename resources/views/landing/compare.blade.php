@extends('layouts.landing')

@section('title', 'Compare Laptops')

@section('content')
<div class="min-h-screen py-12 lg:py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <style>
            @keyframes slideUpFade {
                0% { opacity: 0; transform: translateY(1rem); }
                100% { opacity: 1; transform: translateY(0); }
            }
            .animate-slide-up {
                animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
            }
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        </style>

        <nav class="mb-10 lg:mb-14 animate-slide-up" style="animation-delay: 0s;">
            <ol class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                <li>
                    <a href="{{ route('landing.home') }}" class="hover:text-[#DF5E1D] transition-colors duration-200 flex items-center gap-1.5 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DF5E1D]/50">
                        <iconify-icon icon="solar:home-2-linear" class="text-base" style="stroke-width: 1.5;"></iconify-icon>
                        Home
                    </a>
                </li>
                <li class="flex items-center text-gray-300">
                    <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
                </li>
                <li>
                    <a href="{{ route('landing.search') }}" class="hover:text-[#DF5E1D] transition-colors duration-200 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DF5E1D]/50">Products</a>
                </li>
                <li class="flex items-center text-gray-300">
                    <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
                </li>
                <li class="text-[#363230] truncate">Compare</li>
            </ol>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-12 animate-slide-up" style="animation-delay: 0.1s;">
            <div>
                <h1 class="text-3xl lg:text-4xl font-medium tracking-tight text-[#363230] mb-3">Compare Models</h1>
                <p class="text-base text-gray-500 max-w-xl">See how these devices stack up against each other.</p>
            </div>
            <div class="flex items-center gap-3">
                @if (count($laptops) > 0)
                    <button onclick="clearCompare()" class="text-xs font-medium text-gray-400 hover:text-red-500 transition-colors px-4 py-2.5 rounded-xl border border-gray-200/80 hover:border-red-200">
                        Hapus Semua
                    </button>
                @endif
                <button onclick="openCompareModal()" class="text-xs font-medium text-[#DF5E1D] hover:text-[#c45218] transition-colors px-4 py-2.5 rounded-xl border border-[#DF5E1D]/20 hover:border-[#DF5E1D]/40">
                    + Tambah Produk
                </button>
            </div>
        </div>

        @if (count($laptops) === 0)
            <div class="text-center py-20 animate-slide-up" style="animation-delay: 0.2s;">
                <iconify-icon icon="solar:scale-linear" class="text-6xl text-gray-200 mb-6" style="stroke-width: 1;"></iconify-icon>
                <h2 class="text-xl font-medium text-[#363230] mb-2">Belum ada produk untuk dibandingkan</h2>
                <p class="text-sm text-gray-500 mb-6">Tambahkan produk dari katalog untuk memulai perbandingan.</p>
                <a href="{{ route('landing.search') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#363230] text-white text-sm font-medium hover:bg-[#DF5E1D] transition-all duration-300">
                    <iconify-icon icon="solar:shop-linear" class="text-lg"></iconify-icon>
                    Lihat Katalog
                </a>
            </div>
        @else
            @php $gridCols = count($laptops) === 2 ? 'grid-cols-2' : 'grid-cols-3'; @endphp

            <div class="bg-white rounded-3xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden animate-slide-up" style="animation-delay: 0.2s;">

                <div class=" border-b border-gray-200/60">
                    <div class="grid {{ $gridCols }} relative">
                        @foreach ($laptops as $laptop)
                        <div class="p-6 relative group flex flex-col items-center text-center {{ !$loop->last ? 'border-r border-gray-100' : '' }} {{ $loop->index % 2 === 1 ? 'bg-gray-50/30' : '' }}">
                            <button onclick="removeFromCompare('{{ $laptop->id }}')" class="absolute top-3 right-3 w-7 h-7 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 flex items-center justify-center transition-all shadow-sm" title="Hapus">
                                <iconify-icon icon="solar:close-circle-linear" class="text-lg"></iconify-icon>
                            </button>
                            <div class="relative w-32 h-32 lg:w-40 lg:h-40 mb-6">
                                <img src="{{ $laptop->image_url_full ?? 'https://placehold.co/400x300/363230/DF5E1D?text=ZLM' }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain mix-blend-multiply">
                            </div>
                            <span class="text-[10px] font-medium text-[#DF5E1D] tracking-widest uppercase bg-[#DF5E1D]/10 px-2.5 py-1 rounded-md mb-3">
                                {{ $laptop->brand }}
                            </span>
                            <h3 class="text-base lg:text-lg font-medium tracking-tight text-[#363230] mb-2 line-clamp-1">
                                <a href="{{ route('landing.detail', $laptop) }}" class="hover:text-[#DF5E1D] transition-colors">{{ $laptop->name }}</a>
                            </h3>
                            <div class="text-lg lg:text-xl font-medium tracking-tight text-[#363230] mb-5">
                                Rp {{ number_format($laptop->price, 0, ',', '.') }}
                            </div>
                            <a href="{{ route('landing.detail', $laptop) }}" class="w-full bg-white border border-gray-200 shadow-sm text-[#363230] py-2.5 px-4 rounded-xl text-xs font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 flex items-center justify-center gap-2">
                                <iconify-icon icon="solar:cart-large-2-linear" class="text-sm" style="stroke-width: 1.5;"></iconify-icon>
                                <span class="hidden sm:inline">Lihat Detail</span>
                            </a>
                        </div>
                        @endforeach

                        @for ($i = count($laptops); $i < 3; $i++)
                        <div class="p-6 flex flex-col items-center justify-center text-center border-l border-gray-100 bg-gray-50/20 cursor-pointer hover:bg-gray-100/50 transition-colors" onclick="openCompareModal()">
                            <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <iconify-icon icon="solar:add-circle-linear" class="text-3xl text-gray-300"></iconify-icon>
                            </div>
                            <p class="text-sm text-gray-400 mb-3">Tambah produk untuk dibandingkan</p>
                            <span class="text-xs font-medium text-[#DF5E1D]">
                                + Pilih Produk
                            </span>
                        </div>
                        @endfor
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @php $specs = [ 'processor' => ['label' => 'Processor', 'icon' => 'solar:cpu-linear'], 'ram' => ['label' => 'Memory (RAM)', 'icon' => 'solar:ram-linear'], 'storage' => ['label' => 'Storage', 'icon' => 'solar:database-linear'], 'graphics' => ['label' => 'Graphics', 'icon' => 'solar:graph-new-linear'], 'display' => ['label' => 'Display', 'icon' => 'solar:monitor-linear'], 'weight' => ['label' => 'Weight', 'icon' => 'solar:case-minimalistic-linear'], 'battery_life' => ['label' => 'Battery Life', 'icon' => 'solar:battery-charge-linear'], ]; @endphp

                    @foreach ($specs as $field => $spec)
                    <div class="grid {{ $gridCols }} group hover:bg-orange-50/30 transition-colors duration-300">
                        <div class="col-span-{{ count($laptops) }} lg:col-span-1 text-xs text-center py-2 text-gray-400 bg-gray-50/50 border-b border-gray-50 lg:hidden">{{ $spec['label'] }}</div>
                        <div class="hidden lg:flex items-center p-6 border-r border-gray-100">
                            <div class="flex items-center gap-3 text-sm font-medium text-gray-600">
                                <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-orange-100 transition-colors">
                                    <iconify-icon icon="{{ $spec['icon'] }}" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
                                </div>
                                {{ $spec['label'] }}
                            </div>
                        </div>
                        @foreach ($laptops as $laptop)
                        <div class="p-5 lg:p-6 text-sm text-[#363230] text-center lg:text-left {{ !$loop->parent->last ? 'border-r border-gray-100' : '' }} flex items-center justify-center lg:justify-start {{ $loop->index % 2 === 1 ? 'bg-gray-50/10' : '' }}">
                            @if ($field === 'weight' && $laptop->weight)
                                {{ $laptop->weight }} kg
                            @else
                                {{ $laptop->$field ?? '-' }}
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center mt-10 animate-slide-up" style="animation-delay: 0.3s;">
                <a href="{{ route('landing.search') }}" class="text-sm text-gray-500 hover:text-[#363230] transition-colors inline-flex items-center gap-1">
                    <iconify-icon icon="solar:arrow-left-linear" class="text-base"></iconify-icon>
                    Kembali ke Katalog
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Product Selection Modal -->
<div id="compareModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeCompareModal()"></div>
    <div class="absolute inset-4 sm:inset-x-auto sm:top-[10vh] sm:bottom-auto sm:left-1/2 sm:-translate-x-1/2 sm:w-full sm:max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden max-h-[80vh] flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-lg font-medium text-[#363230]">Pilih Produk</h3>
            <button onclick="closeCompareModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
                <iconify-icon icon="solar:close-linear" class="text-lg"></iconify-icon>
            </button>
        </div>
        <div class="p-4 border-b border-gray-100">
            <input type="text" id="compareSearchInput" placeholder="Cari produk..." oninput="searchCompareProducts(this.value)"
                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
        </div>
        <div id="compareProductList" class="flex-1 overflow-y-auto p-2 space-y-1">
            <div class="text-center py-8 text-sm text-gray-400">Memuat produk...</div>
        </div>
    </div>
</div>

<script>
let compareModalOpen = false;

function openCompareModal() {
    compareModalOpen = true;
    document.getElementById('compareModal').classList.remove('hidden');
    document.getElementById('compareSearchInput').value = '';
    loadCompareProducts('');
}

function closeCompareModal() {
    compareModalOpen = false;
    document.getElementById('compareModal').classList.add('hidden');
}

function loadCompareProducts(search) {
    const list = document.getElementById('compareProductList');
    list.innerHTML = '<div class="text-center py-8 text-sm text-gray-400">Memuat produk...</div>';

    let url = '{{ route('compare.products') }}';
    if (search) url += '?search=' + encodeURIComponent(search);

    fetch(url)
        .then(r => r.json())
        .then(data => {
            const compareIds = JSON.parse(localStorage.getItem('laptopsToCompare') || '[]').map(p => String(p.id));
            if (data.products.length === 0) {
                list.innerHTML = '<div class="text-center py-8 text-sm text-gray-400">Produk tidak ditemukan.</div>';
                return;
            }
            list.innerHTML = data.products.map(p => {
                const disabled = compareIds.includes(String(p.id));
                return '<div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors ' + (disabled ? 'opacity-50' : 'cursor-pointer') + '" ' + (disabled ? '' : 'onclick="addCompareFromModal(\'' + p.id + '\', \'' + p.name.replace(/'/g, "\\'") + '\', \'' + (p.image_url_full || '') + '\')"') + '>' +
                    '<div class="w-12 h-12 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden shrink-0">' +
                        (p.image_url_full ? '<img src="' + p.image_url_full + '" class="w-full h-full object-contain p-1">' : '<iconify-icon icon="solar:laptop-linear" class="text-xl text-gray-300"></iconify-icon>') +
                    '</div>' +
                    '<div class="flex-1 min-w-0">' +
                        '<p class="text-sm font-medium text-[#363230] truncate">' + p.name + '</p>' +
                        '<p class="text-xs text-gray-400">' + p.brand + '</p>' +
                    '</div>' +
                    '<div class="text-right shrink-0">' +
                        '<p class="text-sm font-medium text-[#363230]">Rp ' + new Intl.NumberFormat('id-ID').format(p.price) + '</p>' +
                        (disabled ? '<p class="text-[10px] text-gray-400">Sudah ditambahkan</p>' : '') +
                    '</div>' +
                '</div>';
            }).join('');
        })
        .catch(() => {
            list.innerHTML = '<div class="text-center py-8 text-sm text-red-400">Gagal memuat produk.</div>';
        });
}

function searchCompareProducts(value) {
    loadCompareProducts(value);
}

function addCompareFromModal(id, name, image) {
    fetch('{{ route('compare.add') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ laptop_id: id }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            let compare = JSON.parse(localStorage.getItem('laptopsToCompare') || '[]');
            compare.push({ id, name, image });
            localStorage.setItem('laptopsToCompare', JSON.stringify(compare));
            showToast(res.message, 'success');
            closeCompareModal();
            location.reload();
        } else {
            showToast(res.message, 'info');
        }
    })
    .catch(() => showToast('Gagal menambahkan produk', 'error'));
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('compareSearchInput')?.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCompareModal();
    });
});
</script>
@endsection
