{{-- Modal Auto-fill dari Laptop / SKU Lama --}}
<div id="autofill-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[85vh] animate-scale">
        
        {{-- Header --}}
        <div class="p-5 border-b border-gray-100 flex items-center justify-between shrink-0 bg-gray-50/50">
            <div>
                <h3 class="font-bold text-sm text-[#363230] flex items-center gap-2">
                    <iconify-icon icon="solar:copy-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    Cari & Salin dari Laptop yang Sudah Ada
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Cari berdasarkan SKU Unit, Nama Laptop, atau Brand untuk auto-fill spesifikasi</p>
            </div>
            <button type="button" onclick="closeAutofillModal()" class="p-1.5 text-gray-400 hover:text-gray-700 rounded-xl hover:bg-gray-100 transition">
                <iconify-icon icon="solar:close-circle-linear" class="text-xl"></iconify-icon>
            </button>
        </div>

        {{-- Search Input Box --}}
        <div class="p-4 border-b border-gray-100 shrink-0">
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></iconify-icon>
                <input type="text" id="autofill-search-input" oninput="searchAutofillTemplates(this.value)" placeholder="Ketik SKU (cth: SKU-LEN-...), nama laptop, atau tipe processor..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-semibold text-[#363230] focus:outline-none focus:border-[#DF5E1D] focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all">
            </div>
        </div>

        {{-- Template Results List --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-2.5" id="autofill-results-container">
            <div class="text-center py-12 text-gray-400">
                <iconify-icon icon="solar:magnifer-linear" class="text-4xl text-gray-200 mb-2"></iconify-icon>
                <p class="text-xs">Ketik nama laptop atau SKU untuk mulai mencari template.</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-4 border-t border-gray-100 flex justify-end shrink-0 bg-gray-50">
            <button type="button" onclick="closeAutofillModal()" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-200 rounded-xl transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
let autofillTemplates = [];
window.customAutofillCallback = null;

function openAutofillModal(callback = null) {
    window.customAutofillCallback = callback;
    const modal = document.getElementById('autofill-modal');
    if (!modal) return;
    modal.classList.remove('hidden');
    const input = document.getElementById('autofill-search-input');
    if (input) {
        input.value = '';
        input.focus();
    }
    searchAutofillTemplates('');
}

function closeAutofillModal() {
    const modal = document.getElementById('autofill-modal');
    if (modal) modal.classList.add('hidden');
    window.customAutofillCallback = null;
}

async function searchAutofillTemplates(query = '') {
    const container = document.getElementById('autofill-results-container');
    if (!container) return;
    
    container.innerHTML = `
        <div class="text-center py-8 text-gray-400">
            <iconify-icon icon="solar:refresh-linear" class="text-2xl animate-spin mb-1 text-[#DF5E1D]"></iconify-icon>
            <p class="text-xs">Mencari template laptop...</p>
        </div>
    `;

    try {
        const res = await fetch(`/admin/laptops/api/templates?q=${encodeURIComponent(query)}`);
        const json = await res.json();
        autofillTemplates = json.data || [];

        if (autofillTemplates.length === 0) {
            container.innerHTML = `
                <div class="text-center py-10 text-gray-400">
                    <iconify-icon icon="solar:box-minimalistic-linear" class="text-3xl text-gray-300 mb-1"></iconify-icon>
                    <p class="text-xs">Tidak ditemukan laptop yang cocok dengan "${query}".</p>
                </div>
            `;
            return;
        }

        container.innerHTML = autofillTemplates.map((t, idx) => `
            <div class="p-3.5 bg-white rounded-2xl border border-gray-200/80 hover:border-[#DF5E1D] hover:shadow-md transition-all flex items-start justify-between gap-3 group">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-white bg-[#DF5E1D] px-2 py-0.5 rounded-md">${t.brand || 'Laptop'}</span>
                        ${t.skus && t.skus.length > 0 ? `<span class="text-[10px] font-mono font-bold text-purple-700 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-md">SKU: ${t.skus[0]}</span>` : ''}
                    </div>
                    <h4 class="text-xs font-bold text-[#363230] group-hover:text-[#DF5E1D] transition-colors line-clamp-1">${t.name}</h4>
                    <p class="text-[11px] text-gray-500 mt-1">
                        ${t.processor || '-'} &bull; ${t.ram || '-'} &bull; ${t.storage || '-'} ${t.graphics ? `&bull; ${t.graphics}` : ''}
                    </p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Harga Referensi: <strong class="text-gray-700 font-mono">Rp ${parseInt(t.price || 0).toLocaleString('id-ID')}</strong></p>
                </div>

                <button type="button" onclick="applyTemplate(${idx})" class="px-3.5 py-2 bg-orange-50 hover:bg-[#DF5E1D] text-[#DF5E1D] hover:text-white rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-1.5 shadow-xs">
                    <iconify-icon icon="solar:check-circle-linear" class="text-base"></iconify-icon>
                    <span>Gunakan</span>
                </button>
            </div>
        `).join('');
    } catch (e) {
        container.innerHTML = `<p class="text-center text-xs text-red-500 py-6">Gagal memuat template produk.</p>`;
    }
}

function setFieldValue(selectors, val) {
    if (val === null || val === undefined) return;
    for (const selector of selectors) {
        const el = document.querySelector(selector);
        if (el) {
            el.value = val;
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
            break;
        }
    }
}

function applyTemplate(index) {
    const t = autofillTemplates[index];
    if (!t) return;

    // If custom callback registered (e.g. from dynamic restock row)
    if (typeof window.customAutofillCallback === 'function') {
        const cb = window.customAutofillCallback;
        window.customAutofillCallback = null;
        closeAutofillModal();
        cb(t);
        if (typeof window.showToast === 'function') {
            window.showToast(`Spesifikasi "${t.name}" berhasil diterapkan!`);
        }
        return;
    }

    // 1. Fill Text & Number Inputs across both create laptop and restock create forms
    const fields = [
        'name', 'brand', 'price', 'processor', 'ram', 'storage',
        'graphics', 'display', 'ports', 'camera', 'audio',
        'connectivity', 'color', 'warranty', 'weight', 'battery_life'
    ];

    fields.forEach(field => {
        const val = t[field];
        setFieldValue([
            `[name="${field}"]`,
            `[name="new_laptop[${field}]"]`,
            `#${field}`,
            `#new_laptop_${field}`
        ], val);
    });

    // 2. Fill Brand Dropdown (if select exists)
    const brandSelects = [
        document.getElementById('brand_id'),
        document.getElementById('new_laptop_brand_id'),
        document.querySelector('select[name="brand_id"]'),
        document.querySelector('select[name="new_laptop[brand_id]"]')
    ].filter(Boolean);

    brandSelects.forEach(sel => {
        let matched = false;
        if (t.brand_id) {
            for (let opt of sel.options) {
                if (opt.value === t.brand_id) {
                    sel.value = opt.value;
                    matched = true;
                    break;
                }
            }
        }
        if (!matched && t.brand) {
            const brandLower = t.brand.toLowerCase().trim();
            for (let opt of sel.options) {
                const optText = opt.textContent.toLowerCase().trim();
                const optName = (opt.getAttribute('data-name') || '').toLowerCase().trim();
                if (optText === brandLower || optName === brandLower) {
                    sel.value = opt.value;
                    matched = true;
                    break;
                }
            }
        }
        sel.dispatchEvent(new Event('change', { bubbles: true }));
    });

    // 3. Fill Trix Editors
    const trixFields = ['description', 'kelebihan', 'kekurangan'];
    trixFields.forEach(f => {
        const val = t[f];
        if (!val) return;

        const hiddenInput = document.getElementById(f) || 
                            document.getElementById(`new_laptop_${f}`) || 
                            document.querySelector(`input[name="${f}"]`) || 
                            document.querySelector(`input[name="new_laptop[${f}]"]`);
        if (hiddenInput) {
            hiddenInput.value = val;
        }

        const editor = document.querySelector(`trix-editor[input="${f}"]`) || 
                       document.querySelector(`trix-editor[input="new_laptop_${f}"]`) || 
                       document.querySelector(`trix-editor[input="new_laptop[${f}]"]`);
        if (editor && editor.editor) {
            editor.editor.loadHTML(val);
        }
    });

    // 4. Fill Categories
    if (t.category_ids && Array.isArray(t.category_ids)) {
        // Multi checkboxes
        document.querySelectorAll('input[name="categories[]"], input[name="new_laptop[categories][]"]').forEach(cb => {
            cb.checked = t.category_ids.includes(cb.value);
        });

        // Single select
        const catSelect = document.getElementById('new_laptop_category') || document.querySelector('select[name="new_laptop[categories][]"]');
        if (catSelect && t.category_ids.length > 0) {
            catSelect.value = t.category_ids[0];
            catSelect.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    // 5. If on Restock in existing product mode, check if there's an existing laptop dropdown
    const existingLaptopSelect = document.querySelector('#existing-items-rows select[name^="items["]') || 
                                 document.querySelector('select[name="items[0][laptop_id]"]');
    if (existingLaptopSelect && t.id) {
        existingLaptopSelect.value = t.id;
        existingLaptopSelect.dispatchEvent(new Event('change', { bubbles: true }));
    }

    closeAutofillModal();

    if (typeof window.showToast === 'function') {
        window.showToast(`Spesifikasi laptop "${t.name}" berhasil diterapkan ke formulir!`);
    }
}
</script>
