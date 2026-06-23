@extends('layouts.admin')

@section('title', $laptop->name)
@section('heading', $laptop->name)

@section('content')
<div class="space-y-6">

    {{-- Back & Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.laptops.index') }}" class="text-sm text-gray-500 hover:text-[#363230] transition-colors flex items-center gap-1.5">
            <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon>
            Back to Laptops
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.laptops.edit', $laptop) }}" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50 hover:text-blue-600 hover:border-blue-200 transition-colors flex items-center gap-2">
                <iconify-icon icon="solar:pen-linear"></iconify-icon>
                Edit
            </a>
            <form method="POST" action="{{ route('admin.laptops.destroy', $laptop) }}" onsubmit="return confirm('Delete this laptop permanently?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors flex items-center gap-2">
                    <iconify-icon icon="solar:trash-bin-trash-linear"></iconify-icon>
                    Delete
                </button>
            </form>
        </div>
    </div>

    {{-- Image + Info --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
            {{-- Image --}}
            <div class="bg-gradient-to-b from-gray-50 to-white p-8 flex items-center justify-center border-b lg:border-b-0 lg:border-r border-gray-200/60">
                @if ($laptop->image_url)
                    <img src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full max-w-sm object-contain mix-blend-multiply">
                @else
                    <img src="https://placehold.co/400x300/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full max-w-sm object-contain">
                @endif
            </div>

            {{-- Info --}}
            <div class="lg:col-span-2 p-6 lg:p-8 space-y-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <span class="text-xs font-semibold text-[#DF5E1D] tracking-widest uppercase bg-[#DF5E1D]/10 px-3 py-1 rounded-full border border-[#DF5E1D]/20">
                            {{ $laptop->brand }}
                        </span>
                    </div>
                    <div class="flex-shrink-0">
                        @if ($laptop->stock > 0)
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-xl text-xs font-medium border border-emerald-200/60">
                                <iconify-icon icon="solar:check-circle-linear" class="text-sm"></iconify-icon>
                                In Stock ({{ $laptop->stock }})
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-600 px-3 py-1.5 rounded-xl text-xs font-medium border border-rose-200/60">
                                <iconify-icon icon="solar:close-circle-linear" class="text-sm"></iconify-icon>
                                Out of Stock
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 text-sm">
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wide font-medium">Price</span>
                        <p class="text-2xl font-semibold tracking-tight text-[#363230] mt-1">Rp {{ number_format($laptop->price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wide font-medium">Featured</span>
                        <p class="mt-1">
                            @if ($laptop->is_featured)
                                <span class="inline-flex items-center gap-1 text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md text-xs font-medium">Yes</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md text-xs font-medium">No</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wide font-medium">Categories</span>
                        <div class="flex gap-1.5 flex-wrap mt-1">
                            @forelse ($laptop->categories as $cat)
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md">{{ $cat->name }}</span>
                            @empty
                                <span class="text-xs text-gray-400">—</span>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wide font-medium">Weight</span>
                        <p class="text-[#363230] font-medium mt-1">{{ $laptop->weight ? $laptop->weight . ' kg' : '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Technical Specifications --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-[#363230] flex items-center gap-2">
                <iconify-icon icon="solar:settings-minimalistic-linear" class="text-[#DF5E1D]"></iconify-icon>
                Technical Specifications
            </h3>
        </div>
        <table class="w-full text-sm text-left">
            <tbody class="divide-y divide-gray-50">
                @foreach ([
                    ['Processor', $laptop->processor, 'solar:cpu-linear'],
                    ['RAM', $laptop->ram, 'solar:ram-linear'],
                    ['Storage', $laptop->storage, 'solar:database-linear'],
                    ['Graphics', $laptop->graphics, 'solar:monitor-camera-linear'],
                    ['Display', $laptop->display, 'solar:monitor-linear'],
                    ['Battery Life', $laptop->battery_life, 'solar:battery-charge-linear'],
                ] as $spec)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <th class="px-6 py-4 font-medium text-gray-600 w-1/4">
                            <div class="flex items-center gap-2.5">
                                <iconify-icon icon="{{ $spec[2] }}" class="text-gray-400 text-base"></iconify-icon>
                                {{ $spec[0] }}
                            </div>
                        </th>
                        <td class="px-6 py-4 text-[#363230]">{{ $spec[1] ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Kelebihan & Kekurangan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if ($laptop->kelebihan)
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
            <h3 class="text-base font-semibold text-emerald-800 mb-3 flex items-center gap-2">
                <iconify-icon icon="solar:like-linear" class="text-emerald-500"></iconify-icon>
                Kelebihan
            </h3>
            <div class="prose prose-sm max-w-none text-gray-700">
                {!! $laptop->kelebihan !!}
            </div>
        </div>
        @endif
        @if ($laptop->kekurangan)
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
            <h3 class="text-base font-semibold text-rose-800 mb-3 flex items-center gap-2">
                <iconify-icon icon="solar:dislike-linear" class="text-rose-500"></iconify-icon>
                Kekurangan
            </h3>
            <div class="prose prose-sm max-w-none text-gray-700">
                {!! $laptop->kekurangan !!}
            </div>
        </div>
        @endif
    </div>

    {{-- Variants --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-semibold text-[#363230] flex items-center gap-2">
                <iconify-icon icon="solar:git-branch-linear" class="text-[#DF5E1D]"></iconify-icon>
                Variants
            </h3>
            <div class="flex items-center gap-2">
                @if ($laptop->variants->count() > 0)
                    <a href="{{ route('admin.laptops.variants.index', $laptop) }}" 
                       class="text-xs text-gray-500 hover:text-[#DF5E1D] font-medium transition-colors flex items-center gap-1">
                        <iconify-icon icon="solar:settings-linear" class="text-sm"></iconify-icon>
                        Manage Variants
                    </a>
                @endif
                <a href="{{ route('admin.laptops.variants.create', $laptop) }}" 
                   class="bg-[#DF5E1D] text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-[#c45218] transition-colors flex items-center gap-1">
                    <iconify-icon icon="solar:add-circle-linear" class="text-sm"></iconify-icon>
                    Add Variant
                </a>
            </div>
        </div>

        @if ($laptop->variants->count() > 0)
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Name</th>
                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Price Modifier</th>
                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Stock</th>
                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($laptop->variants as $variant)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-6 font-medium text-[#363230]">{{ $variant->name }}</td>
                            <td class="py-3 px-6 text-gray-600">Rp {{ number_format($variant->price_modifier, 0, ',', '.') }}</td>
                            <td class="py-3 px-6">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium {{ $variant->stock > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $variant->stock }}
                                </span>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.variants.edit', $variant) }}" class="text-gray-400 hover:text-blue-500 transition-colors" title="Edit">
                                        <iconify-icon icon="solar:pen-linear"></iconify-icon>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-8 text-center">
                <iconify-icon icon="solar:git-branch-linear" class="text-3xl text-gray-200 mb-3"></iconify-icon>
                <p class="text-sm text-gray-500 mb-4">Belum ada variant untuk laptop ini.</p>
                <a href="{{ route('admin.laptops.variants.create', $laptop) }}" 
                   class="inline-flex items-center gap-1.5 bg-[#DF5E1D] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                    <iconify-icon icon="solar:add-circle-linear"></iconify-icon>
                    Tambah Variant Pertama
                </a>
            </div>
        @endif
    </div>

    {{-- Description --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
        <h3 class="text-base font-semibold text-[#363230] mb-3 flex items-center gap-2">
            <iconify-icon icon="solar:notes-linear" class="text-[#DF5E1D]"></iconify-icon>
            Description
        </h3>
        <div class="prose prose-sm max-w-none text-gray-700">
            {!! $laptop->description !!}
        </div>
    </div>

</div>
@endsection
