@extends('layouts.admin')

@section('title', 'Varian — ' . $laptop->name)
@section('heading', 'Varian: ' . $laptop->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.laptops.index') }}" class="text-gray-400 hover:text-[#363230] transition-colors">
            <iconify-icon icon="solar:arrow-left-linear" class="text-lg"></iconify-icon>
        </a>
        <p class="text-sm text-gray-500">Manage variants for this laptop</p>
    </div>
    <a href="{{ route('admin.laptops.variants.create', $laptop) }}" class="bg-[#DF5E1D] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors flex items-center gap-2">
        <iconify-icon icon="solar:add-circle-linear"></iconify-icon>
        Add Variant
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Name</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">SKU</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Price Modifier</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Stock</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse ($variants as $variant)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 font-medium text-[#363230]">{{ $variant->name }}</td>
                        <td class="py-4 px-6 text-gray-500 font-mono text-xs">{{ $variant->sku }}</td>
                        <td class="py-4 px-6 text-[#363230]">Rp {{ number_format($variant->price_modifier, 0, ',', '.') }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium {{ $variant->stock > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                {{ $variant->stock }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium {{ $variant->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                {{ $variant->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.variants.edit', $variant) }}" class="text-gray-400 hover:text-blue-500 transition-colors" title="Edit">
                                    <iconify-icon icon="solar:pen-linear"></iconify-icon>
                                </a>
                                <form method="POST" action="{{ route('admin.variants.destroy', $variant) }}" onsubmit="return confirm('Delete this variant?')" class="inline">
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
                        <td colspan="6" class="py-12 text-center text-gray-500">No variants found for this laptop.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($variants->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $variants->links() }}
        </div>
    @endif
</div>
@endsection
