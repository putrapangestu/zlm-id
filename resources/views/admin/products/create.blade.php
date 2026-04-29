@extends('layouts.dashboard')

@section('title', 'Add Product - ZLM.ID Admin')
@section('page-title', 'Add New Product')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Form Container -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8">
            @csrf

            <!-- Basic Information Section -->
            <div>
                <h3 class="text-lg font-semibold text-[#363230] mb-4 flex items-center gap-2">
                    <iconify-icon icon="solar:info-circle-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-[#363230] mb-2">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., MacBook Pro 14 M3">
                    </div>

                    <!-- Brand -->
                    <div>
                        <label for="brand" class="block text-sm font-medium text-[#363230] mb-2">Brand <span class="text-red-500">*</span></label>
                        <select id="brand" name="brand" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all">
                            <option value="">Select Brand</option>
                            <option value="Apple">Apple</option>
                            <option value="Dell">Dell</option>
                            <option value="HP">HP</option>
                            <option value="Lenovo">Lenovo</option>
                            <option value="Asus">Asus</option>
                            <option value="Acer">Acer</option>
                            <option value="MSI">MSI</option>
                            <option value="Razer">Razer</option>
                        </select>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-[#363230] mb-2">Type <span class="text-red-500">*</span></label>
                        <select id="type" name="type" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all">
                            <option value="">Select Type</option>
                            <option value="Ultrabook">Ultrabook</option>
                            <option value="Gaming">Gaming</option>
                            <option value="Business">Business</option>
                            <option value="Workstation">Workstation</option>
                            <option value="2-in-1">2-in-1</option>
                        </select>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-[#363230] mb-2">Price (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="price" name="price" required min="0" step="1000"
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., 19999000">
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-[#363230] mb-2">Stock <span class="text-red-500">*</span></label>
                        <input type="number" id="stock" name="stock" required min="0"
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., 15">
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-[#363230] mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" required rows="4"
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all resize-none"
                            placeholder="Enter product description..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Specifications Section -->
            <div>
                <h3 class="text-lg font-semibold text-[#363230] mb-4 flex items-center gap-2">
                    <iconify-icon icon="solar:settings-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Specifications
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Processor -->
                    <div>
                        <label for="processor" class="block text-sm font-medium text-[#363230] mb-2">Processor <span class="text-red-500">*</span></label>
                        <input type="text" id="processor" name="processor" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., Intel Core i7-13700H">
                    </div>

                    <!-- RAM -->
                    <div>
                        <label for="ram" class="block text-sm font-medium text-[#363230] mb-2">RAM <span class="text-red-500">*</span></label>
                        <input type="text" id="ram" name="ram" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., 16GB DDR5">
                    </div>

                    <!-- Storage -->
                    <div>
                        <label for="storage" class="block text-sm font-medium text-[#363230] mb-2">Storage <span class="text-red-500">*</span></label>
                        <input type="text" id="storage" name="storage" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., 512GB NVMe SSD">
                    </div>

                    <!-- Graphics -->
                    <div>
                        <label for="graphic" class="block text-sm font-medium text-[#363230] mb-2">Graphics <span class="text-red-500">*</span></label>
                        <input type="text" id="graphic" name="graphic" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., NVIDIA RTX 4060 8GB">
                    </div>

                    <!-- Display -->
                    <div>
                        <label for="display" class="block text-sm font-medium text-[#363230] mb-2">Display <span class="text-red-500">*</span></label>
                        <input type="text" id="display" name="display" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., 14" FHD IPS 144Hz">
                    </div>

                    <!-- Battery -->
                    <div>
                        <label for="battery" class="block text-sm font-medium text-[#363230] mb-2">Battery <span class="text-red-500">*</span></label>
                        <input type="text" id="battery" name="battery" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., 72Wh, up to 8 hours">
                    </div>

                    <!-- Weight -->
                    <div>
                        <label for="weight" class="block text-sm font-medium text-[#363230] mb-2">Weight <span class="text-red-500">*</span></label>
                        <input type="text" id="weight" name="weight" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., 1.8 kg">
                    </div>

                    <!-- Minus/Notes -->
                    <div>
                        <label for="minus" class="block text-sm font-medium text-[#363230] mb-2">Minus / Notes</label>
                        <input type="text" id="minus" name="minus"
                            class="w-full bg-gray-50 border border-gray-200 text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                            placeholder="e.g., No SD card slot">
                    </div>
                </div>
            </div>

            <!-- Images Section -->
            <div>
                <h3 class="text-lg font-semibold text-[#363230] mb-4 flex items-center gap-2">
                    <iconify-icon icon="solar:gallery-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Product Images
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#363230] mb-2">Product Images <span class="text-red-500">*</span></label>
                        <div class="flex items-center justify-center w-full">
                            <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <iconify-icon icon="solar:upload-linear" class="text-3xl text-gray-400 mb-2" style="stroke-width: 1.5;"></iconify-icon>
                                    <p class="text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 5MB each (max 5 images)</p>
                                </div>
                                <input id="images" name="images[]" type="file" class="hidden" multiple accept="image/*" />
                            </label>
                        </div>
                    </div>
                    <!-- Preview area -->
                    <div id="image-preview" class="grid grid-cols-4 gap-4">
                        <!-- Image previews will be shown here -->
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-medium text-white bg-[#DF5E1D] hover:bg-orange-600 transition-colors shadow-sm flex items-center gap-2">
                    <iconify-icon icon="solar:check-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        const files = e.target.files;
        for (let i = 0; i < files.length && i < 5; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative aspect-square rounded-xl overflow-hidden border border-gray-200';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                    <button type="button" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600">&times;</button>
                `;
                preview.appendChild(div);
            };

            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
