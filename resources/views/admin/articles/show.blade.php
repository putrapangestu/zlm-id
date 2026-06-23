@extends('layouts.dashboard')

@section('title', 'Detail Artikel — ZLM.ID Admin')
@section('page-title', 'Detail Artikel')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Form Container -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="p-6 sm:p-8 space-y-8">
            <!-- Basic Information Section -->
            <div>
                <h3 class="text-lg font-semibold text-[#363230] mb-4 flex items-center gap-2">
                    <iconify-icon icon="solar:info-circle-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Article Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[#363230] mb-2">Article Name</label>
                        <p class="text-sm text-gray-900">Sample Article 1</p>
                    </div>

                    <!-- Author -->
                    <div>
                        <label class="block text-sm font-medium text-[#363230] mb-2">Author</label>
                        <p class="text-sm text-gray-900">John Doe</p>
                    </div>

                    <!-- Published Date -->
                    <div>
                        <label class="block text-sm font-medium text-[#363230] mb-2">Published Date</label>
                        <p class="text-sm text-gray-900">April 21, 2026</p>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[#363230] mb-2">Description</label>
                        <p class="text-sm text-gray-900">This is a sample description with WYSIWYG content. It can contain formatted text, images, and other rich content elements.</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.articles.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
                    Back to List
                </a>
                <a href="{{ route('admin.articles.edit', $id) }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-white bg-[#DF5E1D] hover:bg-orange-600 transition-colors shadow-sm flex items-center gap-2">
                    <iconify-icon icon="solar:pen-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Edit Article
                </a>
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
