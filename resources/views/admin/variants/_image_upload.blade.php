@php
    $inputId = $inputId ?? 'variant-image-input';
    $dropzoneId = $dropzoneId ?? 'variant-dropzone';
    $previewId = $previewId ?? 'variant-preview';
    $emptyId = $emptyId ?? 'variant-empty';
    $infoId = $infoId ?? 'variant-info';
    $removeBtnId = $removeBtnId ?? 'variant-remove';
    $existing = $existingImage ?? null;
@endphp

<div
    id="{{ $dropzoneId }}"
    class="relative border-2 border-dashed rounded-xl p-6 text-center cursor-pointer transition-all duration-200 hover:border-gray-300 group"
    style="border-color: #e5e7eb;"
>
    <input
        type="file"
        id="{{ $inputId }}"
        name="image"
        accept="image/jpg,image/jpeg,image/png,image/webp"
        class="hidden"
    >

    {{-- Empty state --}}
    <div id="{{ $emptyId }}" class="flex flex-col items-center gap-3 py-4 {{ $existing ? 'hidden' : '' }}">
        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-orange-50 transition-colors">
            <iconify-icon icon="solar:gallery-add-linear" class="text-3xl text-gray-300 group-hover:text-[#DF5E1D] transition-colors"></iconify-icon>
        </div>
        <div>
            <p class="text-sm text-gray-500">
                <span class="text-[#DF5E1D] font-medium">Click to upload</span> or drag & drop
            </p>
            <p class="text-xs text-gray-400 mt-0.5">JPG, PNG or WebP — max 2MB</p>
        </div>
    </div>

    {{-- Preview / existing image --}}
    <div id="{{ $previewId }}" class="flex items-center gap-5 text-left {{ $existing ? '' : 'hidden' }}">
        <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-200">
            <img id="{{ $previewId }}-img"
                 src="{{ $existing ?? '' }}"
                 class="w-full h-full object-cover"
                 alt="Preview"
            >
        </div>
        <div class="flex-1 min-w-0">
            <p id="{{ $infoId }}" class="text-sm font-medium text-[#363230] truncate">
                {{ $existing ? 'Current image' : '' }}
            </p>
            <p class="text-xs text-gray-400 mt-0.5">Click or drag to replace</p>
        </div>
        <button
            type="button"
            id="{{ $removeBtnId }}"
            class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100 hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors flex items-center justify-center {{ $existing ? '' : 'hidden' }}"
            title="Remove image"
        >
            <iconify-icon icon="solar:trash-bin-trash-linear" class="text-sm"></iconify-icon>
        </button>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const el = document.getElementById('{{ $dropzoneId }}');
    const input = document.getElementById('{{ $inputId }}');
    const empty = document.getElementById('{{ $emptyId }}');
    const preview = document.getElementById('{{ $previewId }}');
    const previewImg = document.getElementById('{{ $previewId }}-img');
    const info = document.getElementById('{{ $infoId }}');
    const removeBtn = document.getElementById('{{ $removeBtnId }}');
    const hasExisting = {{ $existing ? 'true' : 'false' }};

    function showPreview(file) {
        if (!file || !file.type.startsWith('image/')) return;

        empty.classList.add('hidden');
        preview.classList.remove('hidden');

        // Show file info
        const sizeKB = (file.size / 1024).toFixed(0);
        info.textContent = file.name + ' (' + sizeKB + ' KB)';

        removeBtn.classList.remove('hidden');

        // Read as data URL for preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Reset border color
        el.style.borderColor = '#e5e7eb';
    }

    function removeImage() {
        input.value = '';
        preview.classList.add('hidden');
        empty.classList.remove('hidden');
        removeBtn.classList.add('hidden');
        previewImg.src = '';
        info.textContent = '';

        // Signal the backend to clear the existing image
        let hidden = document.getElementById('{{ $inputId }}-remove-flag');
        if (!hidden) {
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.id = '{{ $inputId }}-remove-flag';
            hidden.name = 'remove_image';
            hidden.value = '1';
            el.closest('form').appendChild(hidden);
        }

        // Trigger change event so the preview resets
        input.dispatchEvent(new Event('change'));
    }

    // Click on dropzone → open file picker
    el.addEventListener('click', function(e) {
        // Don't trigger if clicking remove button
        if (e.target.closest('#' + '{{ $removeBtnId }}')) return;
        input.click();
    });

    // File selected via picker
    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            showPreview(this.files[0]);
        }
    });

    // Drag events
    el.addEventListener('dragover', function(e) {
        e.preventDefault();
        el.style.borderColor = '#DF5E1D';
        el.classList.add('bg-orange-50/50');
    });

    el.addEventListener('dragleave', function(e) {
        e.preventDefault();
        el.style.borderColor = '#e5e7eb';
        el.classList.remove('bg-orange-50/50');
    });

    el.addEventListener('drop', function(e) {
        e.preventDefault();
        el.style.borderColor = '#e5e7eb';
        el.classList.remove('bg-orange-50/50');
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            input.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files[0]);
        }
    });

    // Remove button
    removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        removeImage();
    });
})();
</script>
@endpush
