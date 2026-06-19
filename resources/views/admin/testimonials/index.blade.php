@extends('layouts.admin')

@section('title', 'Testimonials')
@section('heading', 'Testimonials')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Manage customer testimonials displayed on your website.</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center gap-2 bg-[#DF5E1D] text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition-colors">
            <iconify-icon icon="solar:add-circle-linear" class="text-lg"></iconify-icon>
            Add Testimonial
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                        <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="text-left px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-right px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($testimonials as $testimonial)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                @if ($testimonial->photo)
                                    <img src="{{ Storage::url($testimonial->photo) }}" alt="{{ $testimonial->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-500 border border-gray-200">
                                        {{ substr($testimonial->name, 0, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-[#363230]">{{ $testimonial->name }}</div>
                                @if ($testimonial->position)
                                    <div class="text-xs text-gray-400">{{ $testimonial->position }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex text-[#DF5E1D] gap-0.5">
                                    @for ($i = 0; $i < $testimonial->rating; $i++)
                                        <iconify-icon icon="solar:star-bold" class="text-sm"></iconify-icon>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($testimonial->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="p-2 text-gray-500 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                        <iconify-icon icon="solar:pen-linear" class="text-lg"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-linear" class="text-lg"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <iconify-icon icon="solar:chat-round-dots-linear" class="text-4xl text-gray-300 mb-3"></iconify-icon>
                                    <p class="text-sm text-gray-500 mb-4">No testimonials yet.</p>
                                    <a href="{{ route('admin.testimonials.create') }}" class="text-sm font-medium text-[#DF5E1D] hover:text-[#c45218]">
                                        Add your first testimonial
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($testimonials->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
