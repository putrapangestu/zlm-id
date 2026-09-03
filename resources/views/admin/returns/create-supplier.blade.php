@extends('layouts.admin')

@section('title', 'Buat Retur ke Supplier')
@section('heading', 'Formulir Retur Barang ke Supplier / Distributor')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-bold text-[#363230]">Klaim & Pengembalian Unit ke Supplier</h2>
            <p class="text-xs text-gray-500">Kirim unit cacat/rusak hasil QC atau salah kirim batch restock kembali ke distributor.</p>
        </div>
        <a href="{{ route('admin.returns.index', ['type' => 'supplier']) }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 px-3 py-2">
            &larr; Kembali ke Daftar Retur
        </a>
    </div>

    <form method="POST" action="{{ route('admin.returns.store-supplier') }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-gray-200/70 p-6 shadow-xs space-y-5">
        @csrf

        {{-- Supplier Info --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Supplier / Distributor <span class="text-red-500">*</span></label>
                <input type="text" name="supplier_name" id="supplier_name" value="{{ old('supplier_name') }}" required placeholder="Contoh: PT. Distributor Jaya Laptop"
                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                @error('supplier_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">No. WhatsApp / Telp Supplier</label>
                <input type="text" name="supplier_phone" id="supplier_phone" value="{{ old('supplier_phone') }}" placeholder="0812-xxxx-xxxx"
                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
            </div>
        </div>

        {{-- Link to Batch Restock or Defective QC Unit --}}
        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-4">
            <h4 class="text-xs font-bold text-[#363230] uppercase tracking-wider">Pilih Batch Restock atau Unit Cacat QC</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Dari Batch Restock (Opsional)</label>
                    <select name="restock_id" id="restock_select" onchange="fillSupplierFromRestock(this)" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        <option value="">-- Bukan dari batch spesifik --</option>
                        @foreach ($restocks as $r)
                            <option value="{{ $r->id }}" data-supplier="{{ $r->supplier_name }}" data-phone="{{ $r->supplier_phone }}">
                                {{ $r->restock_number }} ({{ $r->supplier_name }} - {{ $r->purchase_date->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Pilih Unit Gagal QC (Opsional)</label>
                    <select name="product_item_id" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        <option value="">-- Pilih unit cacat --</option>
                        @foreach ($defectiveItems as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->laptop->name }} (Alasan: {{ Str::limit($item->qc_notes, 30) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Reason & Resolution --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alasan Retur <span class="text-red-500">*</span></label>
                <select name="reason" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    <option value="defective_item">Cacat Pabrik / Gagal QC</option>
                    <option value="wrong_item">Salah Kirim Tipe / Spesifikasi</option>
                    <option value="not_as_described">Kondisi Tidak Sesuai PO</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tipe Resolusi Supplier <span class="text-red-500">*</span></label>
                <select name="resolution_type" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    <option value="replacement">Tukar Unit Baru (Penggantian)</option>
                    <option value="refund">Potong Tagihan / Refund Dana</option>
                    <option value="repair">Garansi Servis Resmi Distributor</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Estimasi Nilai Refund / Potongan (Rp)</label>
                <input type="number" step="1000" name="refund_amount" value="{{ old('refund_amount', 0) }}"
                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold focus:outline-none focus:border-[#DF5E1D]">
            </div>
        </div>

        {{-- Detail Notes & Proof Images --}}
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Catatan Detail Kerusakan / Klaim <span class="text-red-500">*</span></label>
            <textarea name="notes" rows="3" required placeholder="Tuliskan nomor seri unit, penjelasan kerusakan fisik, kelengkapan yang dikembalikan, atau nomor RMA supplier..."
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DF5E1D]">{{ old('notes') }}</textarea>
            @error('notes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Foto Bukti Kerusakan / Surat Jalan Retur</label>
            <input type="file" name="proof_images[]" multiple accept="image/*"
                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-600 focus:outline-none">
            <p class="text-[11px] text-gray-400 mt-1">Bisa upload beberapa foto sekaligus (JPG, PNG, WebP).</p>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-[#DF5E1D] hover:bg-[#c45218] text-white px-7 py-3 rounded-xl text-xs font-extrabold transition shadow-md flex items-center gap-2">
                <iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon>
                <span>PROSES RETUR KE SUPPLIER</span>
            </button>
            <a href="{{ route('admin.returns.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 px-4 py-3">Batal</a>
        </div>
    </form>
</div>

<script>
function fillSupplierFromRestock(select) {
    const opt = select.options[select.selectedIndex];
    if (opt && opt.dataset.supplier) {
        document.getElementById('supplier_name').value = opt.dataset.supplier;
        if (opt.dataset.phone) {
            document.getElementById('supplier_phone').value = opt.dataset.phone;
        }
    }
}
</script>
@endsection
