@extends('layouts.admin')

@section('title', 'Laptop')
@section('heading', 'Laptop')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <p class="text-sm text-gray-500">Manage your laptop inventory</p>
    <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('admin.laptops.index') }}" class="relative">
            <iconify-icon icon="solar:minimistic-magnifer-linear" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></iconify-icon>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search laptops..."
                class="w-56 pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
            @if ($search)
                <a href="{{ route('admin.laptops.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    <iconify-icon icon="solar:close-circle-linear"></iconify-icon>
                </a>
            @endif
        </form>
        <a href="{{ route('admin.laptops.create') }}" class="bg-[#DF5E1D] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors flex items-center gap-2 shrink-0">
            <iconify-icon icon="solar:add-circle-linear"></iconify-icon>
            Add Laptop
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Name</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Brand</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Price</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Stock</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Categories</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse ($laptops as $laptop)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 font-medium">
                            <a href="{{ route('admin.laptops.show', $laptop) }}" class="text-[#363230] hover:text-[#DF5E1D] transition-colors">
                                {{ $laptop->name }}
                            </a>
                        </td>
                        <td class="py-4 px-6 text-gray-500">{{ $laptop->brand }}</td>
                        <td class="py-4 px-6 text-[#363230]">Rp {{ number_format($laptop->price, 0, ',', '.') }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium {{ $laptop->stock > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                {{ $laptop->stock }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex gap-1 flex-wrap">
                                @foreach ($laptop->categories as $cat)
                                    <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.laptops.show', $laptop) }}" class="text-gray-400 hover:text-[#DF5E1D] transition-colors" title="Lihat Detail">
                                    <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                </a>
                                <a href="{{ route('admin.laptops.edit', $laptop) }}" class="text-gray-400 hover:text-blue-500 transition-colors" title="Edit">
                                    <iconify-icon icon="solar:pen-linear"></iconify-icon>
                                </a>
                                <a href="{{ route('admin.laptops.variants.index', $laptop) }}" class="text-gray-400 hover:text-[#DF5E1D] transition-colors" title="Variants">
                                    <iconify-icon icon="solar:git-branch-linear"></iconify-icon>
                                </a>
                                <form method="POST" action="{{ route('admin.laptops.destroy', $laptop) }}" onsubmit="return confirm('Delete this laptop?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                                        <iconify-icon icon="solar:trash-bin-trash-linear"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-500">No laptops found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($laptops->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $laptops->links() }}
        </div>
    @endif
</div>
@endsection
