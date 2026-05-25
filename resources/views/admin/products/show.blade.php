@extends('layouts.dashboard')

@section('title', $product['name'] . ' - ZLM.ID Admin')
@section('page-title', 'Product Details')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#363230] transition-colors">
            <iconify-icon icon="solar:alt-arrow-left-linear" style="stroke-width: 1.5;"></iconify-icon>
            Back to Products
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.edit', $product['id']) }}" class="px-4 py-2 rounded-xl text-sm font-medium text-white bg-[#DF5E1D] hover:bg-orange-600 transition-colors shadow-sm flex items-center gap-2">
                <iconify-icon icon="solar:pen-linear" style="stroke-width: 1.5;"></iconify-icon>
                Edit Product
            </a>
            <form action="{{ route('admin.products.destroy', $product['id']) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-red-600 bg-white border border-red-200 hover:bg-red-50 transition-colors flex items-center gap-2">
                    <iconify-icon icon="solar:trash-bin-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Images & Quick Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Main Image -->
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="aspect-square bg-gray-50 flex items-center justify-center p-6">
                    <img src="{{ $product['images'][0] }}" alt="{{ $product['name'] }}" class="w-full h-full object-contain mix-blend-multiply">
                </div>
            </div>

            <!-- Quick Info Card -->
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 space-y-4">
                <div>
                    <div class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-1">Price</div>
                    <div class="text-2xl font-semibold text-[#363230]">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-1">Stock Status</div>
                        <div class="flex items-center gap-2">
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
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-1">Available</div>
                        <div class="text-lg font-semibold text-[#363230]">{{ $product['stock'] }} units</div>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Brand</span>
                        <span class="font-medium text-[#363230]">{{ $product['brand'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-2">
                        <span class="text-gray-500">Type</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600">
                            {{ $product['type'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Detailed Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Name & Description -->
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6">
                <h1 class="text-2xl font-semibold text-[#363230] mb-4">{{ $product['name'] }}</h1>
                <div class="text-gray-600 leading-relaxed">
                    {{ $product['description'] }}
                </div>
            </div>

            <!-- Specifications -->
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-[#363230] flex items-center gap-2">
                        <iconify-icon icon="solar:settings-linear" style="stroke-width: 1.5;"></iconify-icon>
                        Technical Specifications
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="solar:cpu-linear" class="text-gray-500" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Processor</div>
                                <div class="text-sm font-medium text-[#363230] mt-0.5">{{ $product['processor'] }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="solar:memory-card-linear" class="text-gray-500" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">RAM</div>
                                <div class="text-sm font-medium text-[#363230] mt-0.5">{{ $product['ram'] }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="solar:hard-drive-linear" class="text-gray-500" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Storage</div>
                                <div class="text-sm font-medium text-[#363230] mt-0.5">{{ $product['storage'] }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="solar:gallery-linear" class="text-gray-500" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Graphics</div>
                                <div class="text-sm font-medium text-[#363230] mt-0.5">{{ $product['graphic'] }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="solar:screen-full-linear" class="text-gray-500" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Display</div>
                                <div class="text-sm font-medium text-[#363230] mt-0.5">{{ $product['display'] }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="solar:battery-full-linear" class="text-gray-500" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Battery</div>
                                <div class="text-sm font-medium text-[#363230] mt-0.5">{{ $product['battery'] }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="solar:weight-linear" class="text-gray-500" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Weight</div>
                                <div class="text-sm font-medium text-[#363230] mt-0.5">{{ $product['weight'] }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="solar:danger-circle-linear" class="text-gray-500" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Minus / Notes</div>
                                <div class="text-sm font-medium text-[#363230] mt-0.5">{{ $product['minus'] ?? 'None' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Images -->
            @if(count($product['images']) > 1)
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-[#363230] flex items-center gap-2">
                        <iconify-icon icon="solar:gallery-linear" style="stroke-width: 1.5;"></iconify-icon>
                        Additional Images
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(array_slice($product['images'], 1) as $image)
                        <div class="aspect-square rounded-xl border border-gray-200 overflow-hidden bg-gray-50">
                            <img src="{{ $image }}" alt="Product Image" class="w-full h-full object-contain mix-blend-multiply p-2">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Meta Information -->
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="text-[#363230] font-medium ml-2">{{ $product['created_at']->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Last Updated:</span>
                        <span class="text-[#363230] font-medium ml-2">{{ $product['updated_at']->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
