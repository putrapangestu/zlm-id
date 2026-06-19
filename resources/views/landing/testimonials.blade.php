@extends('layouts.landing')

@section('title', 'Testimonials - ZLM.ID')

@section('content')
<!-- Hero Section -->
<div class="bg-[#363230] pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-semibold tracking-tight text-white mb-4">Apa Kata Pelanggan Kami</h1>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">Ribuan orang puas berbelanja laptop second di ZLM.ID Malang.</p>
        </div>
    </div>
</div>

<!-- Testimonials Grid -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($testimonials->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($testimonials as $testimonial)
                    <div class="bg-white p-8 rounded-xl border border-gray-200/60 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex text-[#DF5E1D] mb-4 gap-1">
                            @for ($i = 0; $i < $testimonial->rating; $i++)
                                <iconify-icon icon="solar:star-bold"></iconify-icon>
                            @endfor
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">"{{ $testimonial->content }}"</p>
                        <div class="flex items-center gap-3">
                            @if ($testimonial->photo)
                                <img src="{{ Storage::url($testimonial->photo) }}" alt="{{ $testimonial->name }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-500 border border-gray-200">
                                    {{ substr($testimonial->name, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-[#363230]">{{ $testimonial->name }}</p>
                                @if ($testimonial->position)
                                    <p class="text-xs text-gray-400">{{ $testimonial->position }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($testimonials->hasPages())
                <div class="mt-12">
                    {{ $testimonials->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <iconify-icon icon="solar:chat-round-dots-linear" class="text-5xl text-gray-300 mb-4"></iconify-icon>
                <p class="text-gray-500">Belum ada testimonial saat ini.</p>
            </div>
        @endif
    </div>
</section>
@endsection
