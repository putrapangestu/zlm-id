@extends('layouts.dashboard')

@section('title', 'Edit Artikel — ZLM.ID Admin')
@section('page-title', 'Edit Artikel')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Form Container -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <form action="{{ route('admin.articles.update', $id) }}" method="POST" class="p-6 sm:p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div>
                <h3 class="text-lg font-semibold text-[#363230] mb-4 flex items-center gap-2">
                    <iconify-icon icon="solar:info-circle-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Article Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-[#363230] mb-2">Article Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="Sample Article 1" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., How to Choose the Right Laptop">
                    </div>

                    <!-- Author -->
                    <div>
                        <label for="author" class="block text-sm font-medium text-[#363230] mb-2">Author <span class="text-red-500">*</span></label>
                        <input type="text" id="author" name="author" value="John Doe" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., John Doe">
                    </div>

                    <!-- Published Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-[#363230] mb-2">Published Date <span class="text-red-500">*</span></label>
                        <input type="date" id="date" name="date" value="2026-04-21" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., 2024-01-15">
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-[#363230] mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" required rows="4"
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all resize-none"
                            placeholder="Enter article description...">This is a sample description</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.articles.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-medium text-white bg-[#DF5E1D] hover:bg-orange-600 transition-colors shadow-sm flex items-center gap-2">
                    <iconify-icon icon="solar:check-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Update Article
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any JavaScript functionality here if needed
</script>
@endpush
