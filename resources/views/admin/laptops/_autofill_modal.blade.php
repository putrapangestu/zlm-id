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

function openAutofillModal() {
    const modal = document.getElementById('autofill-modal');
    modal.classList.remove('hidden');
    document.getElementById('autofill-search-input').focus();
    searchAutofillTemplates('');
}

function closeAutofillModal() {
    document.getElementById('autofill-modal').classList.add('hidden');
}

async function searchAutofillTemplates(query = '') {
    const container = document.getElementById('autofill-results-container');
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
                        <span class="text-[10px] font-bold uppercase tracking-wider text-white bg-[#DF5E1D] px-2 py-0.5 rounded-md">${t.brand}</span>
                        ${t.skus && t.skus.length > 0 ? `<span class="text-[10px] font-mono font-bold text-purple-700 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-md">SKU: ${t.skus[0]}</span>` : ''}
                    </div>
                    <h4 class="text-xs font-bold text-[#363230] group-hover:text-[#DF5E1D] transition-colors line-clamp-1">${t.name}</h4>
                    <p class="text-[11px] text-gray-500 mt-1">
                        ${t.processor} &bull; ${t.ram} &bull; ${t.storage} ${t.graphics ? `&bull; ${t.graphics}` : ''}
                    </p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Harga Referensi: <strong class="text-gray-700 font-mono">Rp ${parseInt(t.price).toLocaleString('id-ID')}</strong></p>
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

function applyTemplate(index) {
    const t = autofillTemplates[index];
    if (!t) return;

    // 1. Fill Text Inputs
    const fieldMap = {
        'name': t.name,
        'brand': t.brand,
        'price': t.price,
        'processor': t.processor,
        'ram': t.ram,
        'storage': t.storage,
        'graphics': t.graphics,
        'display': t.display,
        'ports': t.ports,
        'camera': t.camera,
        'audio': t.audio,
        'connectivity': t.connectivity,
        'color': t.color,
        'warranty': t.warranty,
        'weight': t.weight,
        'battery_life': t.battery_life,
    };

    for (const [name, val] of Object.entries(fieldMap)) {
        const el = document.querySelector(`[name="${name}"]`);
        if (el && val !== null && val !== undefined) {
            el.value = val;
        }
    }

    // 2. Fill Trix Editors
    if (t.description) {
        const descInput = document.getElementById('description');
        if (descInput) descInput.value = t.description;
        const descEditor = document.querySelector('trix-editor[input="description"]');
        if (descEditor && descEditor.editor) descEditor.editor.loadHTML(t.description);
    }

    if (t.kelebihan) {
        const kelInput = document.getElementById('kelebihan');
        if (kelInput) kelInput.value = t.kelebihan;
        const kelEditor = document.querySelector('trix-editor[input="kelebihan"]');
        if (kelEditor && kelEditor.editor) kelEditor.editor.loadHTML(t.kelebihan);
    }

    if (t.kekurangan) {
        const kekInput = document.getElementById('kekurangan');
        if (kekInput) kekInput.value = t.kekurangan;
        const kekEditor = document.querySelector('trix-editor[input="kekurangan"]');
        if (kekEditor && kekEditor.editor) kekEditor.editor.loadHTML(t.kekurangan);
    }

    // 3. Check Categories
    if (t.category_ids && Array.isArray(t.category_ids)) {
        document.querySelectorAll('input[name="categories[]"]').forEach(cb => {
            cb.checked = t.category_ids.includes(cb.value);
        });
    }

    closeAutofillModal();
    alert(`Spesifikasi laptop "${t.name}" berhasil diterapkan ke formulir!`);
}
</script>
