@php
    $inputName = $inputName ?? 'images[]';
    $dropzoneId = $dropzoneId ?? 'multi-dropzone';
    $gridId = $gridId ?? 'multi-preview-grid';
    $existingImages = $existingImages ?? collect();
@endphp

<div class="space-y-4">
    {{-- Dropzone --}}
    <div
        id="{{ $dropzoneId }}"
        class="relative border-2 border-dashed rounded-xl p-8 text-center cursor-pointer transition-all duration-200 hover:border-[#DF5E1D]/50 group"
        style="border-color: #e5e7eb;"
    >
        <input
            type="file"
            id="{{ $dropzoneId }}-input"
            name="{{ $inputName }}"
            accept="image/jpg,image/jpeg,image/png,image/webp"
            multiple
            class="hidden"
        >
        <div class="flex flex-col items-center gap-3 py-2">
            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-orange-50 transition-colors">
                <iconify-icon icon="solar:gallery-add-linear" class="text-3xl text-gray-300 group-hover:text-[#DF5E1D] transition-colors"></iconify-icon>
            </div>
            <div>
                <p class="text-sm text-gray-500">
                    <span class="text-[#DF5E1D] font-medium">Click to upload</span> or drag & drop
                </p>
                <p class="text-xs text-gray-400 mt-0.5">JPG, PNG or WebP — max 2MB per file. Bisa pilih multiple.</p>
            </div>
        </div>
    </div>

    {{-- Preview grid for existing images --}}
    @if($existingImages->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach($existingImages as $index => $image)
                <div class="relative group/img rounded-xl overflow-hidden border border-gray-200 bg-gray-50 aspect-square" data-image-id="{{ $image->id }}">
                    <img
                        src="{{ Storage::url($image->image_url) }}"
                        alt="{{ $image->caption ?? 'Product image ' . ($index + 1) }}"
                        class="w-full h-full object-cover"
                    >
                    {{-- Overlay on hover --}}
                    <div class="absolute inset-0 bg-black/0 group-hover/img:bg-black/30 transition-all duration-200 flex items-center justify-center">
                        <label class="opacity-0 group-hover/img:opacity-100 transition-opacity cursor-pointer bg-white rounded-full p-2 shadow-lg hover:bg-red-50" title="Hapus gambar ini">
                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="sr-only peer">
                            <iconify-icon icon="solar:trash-bin-trash-linear" class="text-lg text-gray-400 peer-checked:text-red-500"></iconify-icon>
                        </label>
                    </div>
                    {{-- Sort order badge --}}
                    <div class="absolute top-2 left-2 bg-white/80 backdrop-blur text-xs font-medium text-gray-600 w-6 h-6 rounded-full flex items-center justify-center shadow-sm">
                        {{ $index + 1 }}
                    </div>
                    {{-- Checkmark when selected for deletion --}}
                    <div class="absolute top-2 right-2 hidden peer-checked:block">
                        <div class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-sm">
                            <iconify-icon icon="solar:check-read-linear" class="text-sm"></iconify-icon>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- New uploads preview grid --}}
    <div id="{{ $gridId }}" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>
</div>

@push('scripts')
<script>
(function() {
    const dropzone = document.getElementById('{{ $dropzoneId }}');
    const input = document.getElementById('{{ $dropzoneId }}-input');
    const grid = document.getElementById('{{ $gridId }}');
    let fileIndex = 0;

    // Click to open file picker
    dropzone.addEventListener('click', function(e) {
        if (e.target.closest('label') || e.target.closest('input[type="checkbox"]')) return;
        input.click();
    });

    // File selected
    input.addEventListener('change', function() {
        if (this.files) {
            addFiles(Array.from(this.files));
            this.value = '';
        }
    });

    // Drag events
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropzone.style.borderColor = '#DF5E1D';
        dropzone.classList.add('bg-orange-50/50');
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropzone.style.borderColor = '#e5e7eb';
        dropzone.classList.remove('bg-orange-50/50');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropzone.style.borderColor = '#e5e7eb';
        dropzone.classList.remove('bg-orange-50/50');
        if (e.dataTransfer.files) {
            addFiles(Array.from(e.dataTransfer.files));
        }
    });

    function addFiles(files) {
        const validFiles = files.filter(f => f.type.startsWith('image/'));

        validFiles.forEach(function(file) {
            const idx = fileIndex++;
            const card = document.createElement('div');
            card.className = 'relative rounded-xl overflow-hidden border border-gray-200 bg-gray-50 aspect-square';
            card.dataset.previewIndex = idx;

            const reader = new FileReader();
            reader.onload = function(e) {
                card.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview">
                    <div class="absolute inset-0 bg-black/0 hover:bg-black/30 transition-all duration-200 flex items-center justify-center">
                        <button type="button" onclick="this.closest('[data-preview-index]').remove()" class="opacity-0 hover:opacity-100 transition-opacity bg-white rounded-full p-2 shadow-lg hover:bg-red-50" title="Hapus">
                            <iconify-icon icon="solar:trash-bin-trash-linear" class="text-lg text-gray-400 hover:text-red-500"></iconify-icon>
                        </button>
                    </div>
                    <div class="absolute top-2 left-2 bg-white/80 backdrop-blur text-xs font-medium text-gray-600 w-6 h-6 rounded-full flex items-center justify-center shadow-sm">
                        Baru
                    </div>
                `;
            };
            reader.readAsDataURL(file);

            grid.appendChild(card);
        });
    }
})();
</script>
@endpush
