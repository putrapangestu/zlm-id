@extends('layouts.dashboard')

@section('title', 'Manajemen Produk — ZLM.ID Admin')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-[#363230]">Laptop Products</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your laptop inventory and product listings</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="stroke-width: 1.5;"></iconify-icon>
                <input type="text" placeholder="Search products..." class="w-64 bg-white border border-gray-200 text-sm text-[#363230] placeholder-gray-400 rounded-xl py-2 pl-9 pr-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all">
            </div>
            <a href="{{ route('admin.products.create') }}" class="bg-[#DF5E1D] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-orange-600 transition-colors shadow-sm flex items-center gap-2">
                <iconify-icon icon="solar:plus-linear" style="stroke-width: 1.5;"></iconify-icon>
                Add Product
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Product</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Brand</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Type</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Price</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Stock</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center p-1 overflow-hidden">
                                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-contain mix-blend-multiply">
                                </div>
                                <div>
                                    <div class="font-medium text-[#363230]">{{ $product['name'] }}</div>
                                    <div class="text-xs text-gray-500">Added {{ $product['created_at']->diffForHumans() }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-gray-500">{{ $product['brand'] }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600">
                                {{ $product['type'] }}
                            </span>
                        </td>
                        <td class="py-4 px-6 font-medium text-[#363230]">Rp {{ number_format($product['price'], 0, ',', '.') }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $product['stock'] }} units</td>
                        <td class="py-4 px-6">
                            @if($product['stock'] > 10)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-100/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    In Stock
                                </span>
                            @elseif($product['stock'] > 0)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-orange-50 text-orange-600 border border-orange-100/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                    Low Stock
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-100/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Out of Stock
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-100 transition-opacity">
                                <a href="{{ route('admin.products.show', $product['id']) }}" class="p-2 text-gray-500 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="View">
                                    <iconify-icon icon="solar:eye-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </a>
                                <a href="{{ route('admin.products.edit', $product['id']) }}" class="p-2 text-gray-500 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                    <iconify-icon icon="solar:pen-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product['id']) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <iconify-icon icon="solar:trash-bin-2-linear" style="stroke-width: 1.5;"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 px-6 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-3">
                                <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-4xl text-gray-300" style="stroke-width: 1.5;"></iconify-icon>
                                <p>No products found. Start by adding your first product.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <div class="text-xs text-gray-500">
                Showing <span class="font-medium">1</span> to <span class="font-medium">{{ count($products) }}</span> of <span class="font-medium">{{ count($products) }}</span> products
            </div>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Previous
                </button>
                <button class="px-3 py-1.5 text-xs font-medium text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Next
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any JavaScript functionality here if needed
</script>
@endpush
