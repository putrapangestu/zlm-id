@extends('layouts.landing')

@section('title', 'Smart Search — ZLM.ID')

@section('content')
<!-- Smart Search Hero -->
<div class="relative bg-[#363230] pt-24 pb-16 lg:pt-32 lg:pb-20 overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-[#DF5E1D] opacity-15 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[#DF5E1D] text-xs font-medium mb-4">
                <iconify-icon icon="solar:magic-stick-3-bold"></iconify-icon>
                AI-Powered Matching
            </div>
            <h1 class="text-3xl md:text-4xl font-semibold tracking-tight text-white mb-3">
                Smart Search
            </h1>
            <p class="text-gray-400 text-sm max-w-md mx-auto">
                Temukan laptop terbaik sesuai budget dan kebutuhanmu. Sistem kami akan memberikan rekomendasi dengan skor kecocokan.
            </p>
        </div>

        <!-- Search Form -->
        <form action="{{ route('landing.smart-search.post') }}" method="POST" class="bg-white rounded-2xl shadow-xl p-6 lg:p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Budget -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Budget Maksimal</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-medium">Rp</span>
                        </div>
                        <input
                            type="text"
                            name="budget_display"
                            id="budget_display"
                            value="{{ old('budget_display', isset($budget) ? number_format($budget, 0, ',', '.') : '') }}"
                            placeholder="Contoh: 10.000.000"
                            required
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 outline-none focus:border-[#DF5E1D] focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all"
                            oninput="formatBudget(this)"
                        >
                        <input type="hidden" name="budget" id="budget_hidden" value="{{ old('budget', $budget ?? '') }}">
                    </div>
                    @error('budget')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prioritas Utama</label>
                    <div class="grid grid-cols-3 gap-1.5">
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="cpu" class="peer sr-only" {{ old('priority', $priority ?? 'all') === 'cpu' ? 'checked' : '' }}>
                            <div class="px-2 py-2 text-center text-xs font-medium rounded-lg border border-gray-200 peer-checked:border-[#DF5E1D] peer-checked:bg-[#DF5E1D]/5 peer-checked:text-[#DF5E1D] transition-all hover:bg-gray-50">
                                <iconify-icon icon="solar:cpu-linear" class="text-base block mx-auto mb-0.5"></iconify-icon>
                                CPU
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="ram" class="peer sr-only" {{ old('priority', $priority ?? '') === 'ram' ? 'checked' : '' }}>
                            <div class="px-2 py-2 text-center text-xs font-medium rounded-lg border border-gray-200 peer-checked:border-[#DF5E1D] peer-checked:bg-[#DF5E1D]/5 peer-checked:text-[#DF5E1D] transition-all hover:bg-gray-50">
                                <iconify-icon icon="solar:ram-linear" class="text-base block mx-auto mb-0.5"></iconify-icon>
                                RAM
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="storage" class="peer sr-only" {{ old('priority', $priority ?? '') === 'storage' ? 'checked' : '' }}>
                            <div class="px-2 py-2 text-center text-xs font-medium rounded-lg border border-gray-200 peer-checked:border-[#DF5E1D] peer-checked:bg-[#DF5E1D]/5 peer-checked:text-[#DF5E1D] transition-all hover:bg-gray-50">
                                <iconify-icon icon="solar:database-linear" class="text-base block mx-auto mb-0.5"></iconify-icon>
                                Storage
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="gpu" class="peer sr-only" {{ old('priority', $priority ?? '') === 'gpu' ? 'checked' : '' }}>
                            <div class="px-2 py-2 text-center text-xs font-medium rounded-lg border border-gray-200 peer-checked:border-[#DF5E1D] peer-checked:bg-[#DF5E1D]/5 peer-checked:text-[#DF5E1D] transition-all hover:bg-gray-50">
                                <iconify-icon icon="solar:monitor-smartphone-linear" class="text-base block mx-auto mb-0.5"></iconify-icon>
                                GPU
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="all" class="peer sr-only" {{ old('priority', $priority ?? 'all') === 'all' ? 'checked' : '' }}>
                            <div class="px-2 py-2 text-center text-xs font-medium rounded-lg border border-gray-200 peer-checked:border-[#DF5E1D] peer-checked:bg-[#DF5E1D]/5 peer-checked:text-[#DF5E1D] transition-all hover:bg-gray-50">
                                <iconify-icon icon="solar:stars-linear" class="text-base block mx-auto mb-0.5"></iconify-icon>
                                Semua
                            </div>
                        </label>
                    </div>
                    @error('priority')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Usage & Brand -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kebutuhan</label>
                        <select name="usage" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 outline-none focus:border-[#DF5E1D] focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all appearance-none cursor-pointer" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%239CA3AF%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px;">
                            <option value="all" {{ old('usage', $usage ?? 'all') === 'all' ? 'selected' : '' }}>Semua Kebutuhan</option>
                            <option value="office" {{ old('usage', $usage ?? '') === 'office' ? 'selected' : '' }}>Kantor / Office</option>
                            <option value="programming" {{ old('usage', $usage ?? '') === 'programming' ? 'selected' : '' }}>Programming</option>
                            <option value="design" {{ old('usage', $usage ?? '') === 'design' ? 'selected' : '' }}>Desain / Editing</option>
                            <option value="gaming" {{ old('usage', $usage ?? '') === 'gaming' ? 'selected' : '' }}>Gaming</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand (opsional)</label>
                        <select name="brand" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 outline-none focus:border-[#DF5E1D] focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all appearance-none cursor-pointer" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%239CA3AF%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px;">
                            <option value="">Semua Brand</option>
                            @foreach($brands as $brandItem)
                                <option value="{{ $brandItem }}" {{ old('brand', $brand ?? '') === $brandItem ? 'selected' : '' }}>{{ $brandItem }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-[#DF5E1D] hover:bg-[#c45218] text-white font-medium rounded-xl text-sm transition-all duration-200 shadow-lg shadow-[#DF5E1D]/20 hover:shadow-[#DF5E1D]/30 hover:-translate-y-0.5 active:translate-y-0">
                    <iconify-icon icon="solar:magnifer-linear" class="text-lg"></iconify-icon>
                    Cari Rekomendasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Results Section -->
<section class="py-12 lg:py-16 bg-gray-50 min-h-[60vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(isset($results) && isset($totalLaptops))
            <!-- Results Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-[#363230]">Hasil Rekomendasi</h2>
                    <p class="text-sm text-gray-500 mt-1">Ditemukan <span class="font-semibold text-[#DF5E1D]">{{ $totalLaptops }}</span> laptop yang cocok dengan budget Rp {{ number_format($budget, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <iconify-icon icon="solar:info-circle-linear"></iconify-icon>
                    Diurutkan berdasarkan skor kecocokan
                </div>
            </div>

            @if($results->count() > 0)
                <!-- Results Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($results as $index => $laptop)
                        <div class="bg-white rounded-xl border border-gray-200/80 hover:border-gray-300 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col group">
                            <!-- Score Badge -->
                            <div class="absolute top-3 left-3 z-10">
                                @php
                                    $scoreColor = $laptop->match_score >= 80 ? 'bg-emerald-500' : ($laptop->match_score >= 60 ? 'bg-amber-400' : 'bg-red-400');
                                    $scoreTextColor = $laptop->match_score >= 80 ? 'text-emerald-50' : ($laptop->match_score >= 60 ? 'text-amber-50' : 'text-red-50');
                                @endphp
                                <div class="{{ $scoreColor }} px-3 py-1.5 rounded-lg text-xs font-bold {{ $scoreTextColor }} shadow-lg backdrop-blur-sm">
                                    <iconify-icon icon="solar:stars-bold" class="text-xs"></iconify-icon>
                                    {{ $laptop->match_score }}%
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="relative h-48 bg-gray-50 overflow-hidden flex items-center justify-center p-4 border-b border-gray-100">
                                @if($laptop->image_url)
                                    <img src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <img src="https://placehold.co/600x400/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                                @endif

                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm border border-gray-200 text-[#363230] px-2.5 py-1 rounded-md text-xs font-medium shadow-sm">
                                    {{ $laptop->categories->first()?->name ?? 'Laptop' }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5 flex flex-col flex-grow">
                                <p class="text-xs text-gray-400 font-medium tracking-wide uppercase mb-1">{{ $laptop->brand }}</p>
                                <h3 class="text-sm font-semibold text-[#363230] mb-3 line-clamp-2 leading-snug group-hover:text-[#DF5E1D] transition-colors">
                                    {{ $laptop->name }}
                                </h3>

                                <!-- Specs Single Line -->
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded-md text-xs text-gray-600">
                                        <iconify-icon icon="solar:cpu-linear" class="text-[10px] text-gray-400"></iconify-icon>
                                        {{ Str::limit($laptop->processor, 25) }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded-md text-xs text-gray-600">
                                        <iconify-icon icon="solar:ram-linear" class="text-[10px] text-gray-400"></iconify-icon>
                                        {{ $laptop->ram }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded-md text-xs text-gray-600">
                                        <iconify-icon icon="solar:database-linear" class="text-[10px] text-gray-400"></iconify-icon>
                                        {{ $laptop->storage }}
                                    </span>
                                    @if($laptop->graphics)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded-md text-xs text-gray-600">
                                            <iconify-icon icon="solar:monitor-smartphone-linear" class="text-[10px] text-gray-400"></iconify-icon>
                                            {{ Str::limit($laptop->graphics, 20) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Score Breakdown -->
                                <div class="grid grid-cols-5 gap-1 mb-4">
                                    @foreach(['budget' => 'Budget', 'cpu' => 'CPU', 'ram' => 'RAM', 'storage' => 'SSD', 'gpu' => 'GPU'] as $key => $label)
                                        @php
                                            $score = $laptop->scores[$key] ?? 0;
                                            $barColor = $score >= 80 ? 'bg-emerald-400' : ($score >= 60 ? 'bg-amber-400' : 'bg-gray-300');
                                        @endphp
                                        <div class="text-center">
                                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden mb-1">
                                                <div class="{{ $barColor }} h-full rounded-full" style="width: {{ $score }}%"></div>
                                            </div>
                                            <span class="text-[9px] text-gray-400 font-medium">{{ $label }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Price & CTA -->
                                <div class="pt-3 border-t border-gray-100 mt-auto">
                                    <div class="flex items-center justify-between">
                                        <p class="text-lg font-semibold tracking-tight text-[#363230]">
                                            Rp {{ number_format($laptop->price, 0, ',', '.') }}
                                        </p>
                                        <a href="{{ route('landing.detail', $laptop) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#DF5E1D]/10 text-[#DF5E1D] text-xs font-medium rounded-lg hover:bg-[#DF5E1D] hover:text-white transition-all">
                                            Detail
                                            <iconify-icon icon="solar:arrow-right-linear" class="text-xs"></iconify-icon>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <iconify-icon icon="solar:search-sad-linear" class="text-4xl text-gray-300"></iconify-icon>
                    </div>
                    <h3 class="text-lg font-semibold text-[#363230] mb-2">Tidak ada laptop ditemukan</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">
                        Tidak ada laptop yang sesuai dengan kriteria pencarianmu. Coba naikkan budget atau ubah filter prioritas.
                    </p>
                    <div class="flex flex-wrap gap-3 justify-center">
                        <a href="{{ route('landing.smart-search') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white text-sm font-medium rounded-xl transition-all">
                            <iconify-icon icon="solar:refresh-linear"></iconify-icon>
                            Reset Pencarian
                        </a>
                        <a href="{{ route('landing.search') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-all">
                            <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                            Browse Semua Laptop
                        </a>
                    </div>
                </div>
            @endif
        @else
            <!-- Initial State -->
            <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                <div class="w-20 h-20 bg-[#DF5E1D]/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <iconify-icon icon="solar:magic-stick-3-bold" class="text-4xl text-[#DF5E1D]"></iconify-icon>
                </div>
                <h3 class="text-lg font-semibold text-[#363230] mb-2">Masukkan kriteria pencarian</h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto">
                    Isi form di atas dengan budget dan prioritas kebutuhanmu. Sistem kami akan menghitung skor kecocokan untuk setiap laptop yang tersedia.
                </p>
            </div>
        @endif
    </div>
</section>

<script>
    function formatBudget(input) {
        // Remove non-numeric characters
        let value = input.value.replace(/[^0-9]/g, '');
        
        // Format with dots
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        
        input.value = value;
        document.getElementById('budget_hidden').value = value.replace(/\./g, '');
    }

    // Format on load
    document.addEventListener('DOMContentLoaded', function() {
        const display = document.getElementById('budget_display');
        if (display.value) {
            formatBudget(display);
        }
    });
</script>
@endsection
