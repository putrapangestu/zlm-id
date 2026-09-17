@extends('layouts.admin')

@section('title', 'Detail Restock: ' . $restock->restock_number . ' — ZLM.ID Admin')
@section('heading', 'Detail Penerimaan Restock')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-sm text-gray-400">
            <a href="{{ route('admin.restocks.index') }}" class="hover:text-[#DF5E1D] transition-colors">Restock Barang</a>
            <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
            <span class="text-[#363230] font-semibold font-mono">{{ $restock->restock_number }}</span>
        </div>

        <div class="flex items-center gap-3">
            @can('restock.print')
            <a href="{{ route('admin.restocks.print', $restock) }}" target="_blank" class="px-4 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center gap-2">
                <iconify-icon icon="solar:printer-linear" class="text-base"></iconify-icon>
                <span>Cetak Dot Matrix Kertas Besar</span>
            </a>
            @endcan
        </div>
    </div>

    {{-- Restock Info Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Batch Info --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-5 space-y-2.5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">No. Restock</span>
                @if($restock->status === 'completed')
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">QC Selesai</span>
                @elseif($restock->status === 'partially_checked')
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">Sebagian QC</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pending QC</span>
                @endif
            </div>
            <p class="text-lg font-bold font-mono text-[#363230]">{{ $restock->restock_number }}</p>
            <div class="pt-2 border-t border-gray-100 text-xs text-gray-500 space-y-1">
                <div>Tanggal: <strong>{{ $restock->purchase_date->format('d M Y') }}</strong></div>
                <div>Petugas: <strong>{{ $restock->creator->name }}</strong></div>
            </div>
        </div>

        {{-- Card 2: Supplier Info --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-5 space-y-2.5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Data Supplier</span>
                @if($restock->supplier_id)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200">Master Data</span>
                @endif
            </div>
            <p class="text-base font-bold text-[#363230]">{{ $restock->supplier_name }}</p>
            <div class="pt-2 border-t border-gray-100 text-xs text-gray-500 space-y-1">
                <div>No. WA/Telp: <strong>{{ $restock->supplier_phone ?? '-' }}</strong></div>
                <div>No. Invoice: <strong>{{ $restock->invoice_number ?? '-' }}</strong></div>
            </div>
        </div>

        {{-- Card 3: Shipping Status --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-5 space-y-2.5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Pengiriman</span>
                @if($restock->shipping_status === 'received')
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Diterima</span>
                @elseif($restock->shipping_status === 'in_transit')
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 animate-pulse">Dalam Perjalanan</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending Kirim</span>
                @endif
            </div>
            <div>
                <p class="text-xs text-gray-600 font-medium">Kurir: <strong>{{ $restock->shipping_courier ?: 'Belum diatur' }}</strong></p>
                <p class="text-xs text-gray-600 font-medium mt-0.5">Resi: <strong class="font-mono">{{ $restock->tracking_number ?: '-' }}</strong></p>
            </div>
            <div class="pt-2 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('shipping-modal').classList.remove('hidden')"
                    class="w-full py-1.5 px-2 bg-gray-100 hover:bg-gray-200 text-[#363230] rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-1">
                    <iconify-icon icon="solar:pen-linear"></iconify-icon>
                    <span>Update Pengiriman</span>
                </button>
            </div>
        </div>

        {{-- Card 4: Financial Summary --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-5 space-y-2.5">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Total Nilai Pembelian (HPP)</span>
            <p class="text-xl font-extrabold text-emerald-600 font-mono">Rp {{ number_format($restock->total_amount, 0, ',', '.') }}</p>
            <div class="pt-2 border-t border-gray-100 text-xs text-gray-500 space-y-1">
                <div>Total Unit: <strong>{{ $restock->items->sum('quantity') }} Unit</strong></div>
                <div>QC Lolos: <strong class="text-emerald-600">{{ $restock->productItems->where('qc_status', 'passed')->count() }}</strong> &bull; Pending: <strong class="text-amber-600">{{ $restock->productItems->where('qc_status', 'pending')->count() }}</strong></div>
            </div>
        </div>
    </div>

    {{-- MODAL: Update Status Pengiriman --}}
    <div id="shipping-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl p-6 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <h3 class="text-sm font-bold text-[#363230] flex items-center gap-2">
                    <iconify-icon icon="solar:delivery-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    <span>Update Status Pengiriman Restock</span>
                </h3>
                <button type="button" onclick="document.getElementById('shipping-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700">
                    <iconify-icon icon="solar:close-circle-linear" class="text-xl"></iconify-icon>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.restocks.shipping-status', $restock) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Status Pengiriman <span class="text-red-500">*</span></label>
                    <select name="shipping_status" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        <option value="pending" @selected($restock->shipping_status === 'pending')>Pending (Menunggu Pengiriman Supplier)</option>
                        <option value="in_transit" @selected($restock->shipping_status === 'in_transit')>Dalam Perjalanan (Sedang Dikirim)</option>
                        <option value="received" @selected($restock->shipping_status === 'received')>Sudah Diterima di Gudang / Toko</option>
                        <option value="completed" @selected($restock->shipping_status === 'completed')>Selesai</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kurir / Ekspedisi</label>
                    <input type="text" name="shipping_courier" value="{{ $restock->shipping_courier }}" placeholder="JNE / J&T / Indah Kargo / dll"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Resi Pengiriman</label>
                    <input type="text" name="tracking_number" value="{{ $restock->tracking_number }}" placeholder="Nomor resi..."
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('shipping-modal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Restock Items Purchased --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                <iconify-icon icon="solar:cart-large-2-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                Ringkasan Produk Pembelian
            </h3>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/60 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                    <th class="py-3 px-6">Produk & Varian</th>
                    <th class="py-3 px-6 text-center">Jumlah Unit</th>
                    <th class="py-3 px-6">Harga Beli / HPP</th>
                    <th class="py-3 px-6 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs">
                @foreach($restock->items as $item)
                <tr>
                    <td class="py-4 px-6">
                        <span class="font-bold text-[#363230] block">{{ $item->laptop->name }}</span>
                        <span class="text-gray-400">{{ $item->variant?->name ?? 'Standard' }}</span>
                    </td>
                    <td class="py-4 px-6 text-center font-bold text-[#363230]">{{ $item->quantity }} Unit</td>
                    <td class="py-4 px-6 font-mono">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                    <td class="py-4 px-6 text-right font-mono font-bold text-emerald-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Physical Product Items Generated (QC Units) --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                    <iconify-icon icon="solar:checklist-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    Daftar Unit Fisik & Status Quality Control
                </h3>
                <p class="text-xs text-gray-500 mt-1">Setiap unit fisik wajib diinspeksi. Stok jual hanya bertambah jika unit lolos dan diberi SKU.</p>
            </div>
            <a href="{{ route('admin.qc.index') }}" class="text-xs font-semibold text-[#DF5E1D] hover:underline">
                Buka Menu QC &rarr;
            </a>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/60 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                    <th class="py-3 px-6">#</th>
                    <th class="py-3 px-6">Laptop & Varian</th>
                    <th class="py-3 px-6">SKU Jual (Setelah QC)</th>
                    <th class="py-3 px-6">Status QC</th>
                    <th class="py-3 px-6">Inspektor / Waktu</th>
                    <th class="py-3 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs">
                @foreach($restock->productItems as $index => $pItem)
                <tr class="hover:bg-gray-50/50">
                    <td class="py-3.5 px-6 text-gray-400">{{ $index + 1 }}</td>
                    <td class="py-3.5 px-6">
                        <span class="font-medium text-[#363230] block">{{ $pItem->laptop->name }}</span>
                        <span class="text-gray-400 text-[11px]">{{ $pItem->variant?->name ?? 'Standard' }}</span>
                    </td>
                    <td class="py-3.5 px-6">
                        @if($pItem->sku)
                            <span class="font-mono font-bold bg-gray-100 px-2 py-0.5 rounded border border-gray-200 text-[#363230]">{{ $pItem->sku }}</span>
                        @else
                            <span class="text-amber-600 italic">Belum Ada SKU</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-6">
                        @if($pItem->qc_status === 'passed')
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Lolos QC (Stok Jual)
                            </span>
                        @elseif($pItem->qc_status === 'failed')
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                Gagal QC (Defect)
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                Pending QC
                            </span>
                        @endif
                    </td>
                    <td class="py-3.5 px-6">
                        @if($pItem->inspector)
                            <span class="text-[#363230] font-medium block">{{ $pItem->inspector->name }}</span>
                            <span class="text-gray-400 text-[11px]">{{ $pItem->qc_at?->format('d/m/y H:i') }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-6 text-right">
                        @can('qc.inspect')
                        <a href="{{ route('admin.qc.inspect', $pItem) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-[#DF5E1D] text-white hover:bg-[#c45218] rounded-lg text-xs font-medium transition-colors shadow-sm">
                            <iconify-icon icon="solar:checklist-linear"></iconify-icon>
                            <span>{{ $pItem->qc_status === 'pending' ? 'Periksa' : 'Edit QC' }}</span>
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
