@extends('layouts.admin')

@section('title', 'Categories')
@section('heading', 'Categories')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <p class="text-sm text-gray-500">Manage product categories</p>
    <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="relative">
            <iconify-icon icon="solar:minimistic-magnifer-linear" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></iconify-icon>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search categories..."
                class="w-56 pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
            @if ($search)
                <a href="{{ route('admin.categories.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    <iconify-icon icon="solar:close-circle-linear"></iconify-icon>
                </a>
            @endif
        </form>
        <a href="{{ route('admin.categories.create') }}" class="bg-[#DF5E1D] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors flex items-center gap-2 shrink-0">
            <iconify-icon icon="solar:add-circle-linear"></iconify-icon>
            Add Category
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Name</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Slug</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Products</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse ($categories as $cat)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                @if ($cat->icon)
                                    <iconify-icon icon="{{ $cat->icon }}" class="text-[#DF5E1D] text-lg"></iconify-icon>
                                @endif
                                <span class="font-medium text-[#363230]">{{ $cat->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-gray-500">{{ $cat->slug }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $cat->laptops_count }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium {{ $cat->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                {{ $cat->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="text-gray-400 hover:text-blue-500 transition-colors" title="Edit">
                                    <iconify-icon icon="solar:pen-linear"></iconify-icon>
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')" class="inline">
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
                        <td colspan="5" class="py-12 text-center text-gray-500">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($categories->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
