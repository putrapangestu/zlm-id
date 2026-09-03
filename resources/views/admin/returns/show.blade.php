@extends('layouts.admin')

@section('title', 'Detail Retur: ' . $return->return_number . ' — ZLM.ID Admin')
@section('heading', 'Detail & Proses Retur Barang')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400">
        <a href="{{ route('admin.returns.index') }}" class="hover:text-[#DF5E1D] transition-colors">Retur Barang</a>
        <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
        <span class="text-[#363230] font-semibold font-mono">{{ $return->return_number }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 cols: Return Information & Proof --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Product & Order Card --}}
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-gray-100">
                    <iconify-icon icon="solar:box-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    Informasi Barang & Pesanan
                </h3>

                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden shrink-0 flex items-center justify-center">
                        @if($return->orderItem->laptop->image_url_full)
                            <img src="{{ $return->orderItem->laptop->image_url_full }}" alt="" class="w-full h-full object-cover">
                        @else
                            <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-gray-400 text-2xl"></iconify-icon>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-[#363230]">{{ $return->orderItem->laptop->name }}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Varian: {{ $return->orderItem->variant?->name ?? 'Standard' }} &bull; Qty: 1 Unit</p>
                        <p class="text-xs text-gray-500 font-mono font-semibold mt-1">Nilai Barang: Rp {{ number_format($return->orderItem->subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3 bg-gray-50 rounded-xl text-xs">
                    <div>
                        <span class="text-gray-400 block">No. Pesanan:</span>
                        <a href="{{ route('admin.transactions.show', $return->order) }}" class="font-semibold text-[#DF5E1D] hover:underline">{{ $return->order->order_number }}</a>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Pelanggan:</span>
                        <span class="font-semibold text-[#363230]">{{ $return->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">WhatsApp:</span>
                        <span class="font-semibold text-[#363230]">{{ $return->user->phone_number ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Reason & Customer Proof --}}
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-gray-100">
                    <iconify-icon icon="solar:chat-round-dots-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    Alasan Retur & Bukti Pelanggan
                </h3>

                <div>
                    <span class="text-xs text-gray-400 block uppercase font-medium">Alasan Pengajuan:</span>
                    <span class="text-sm font-bold text-rose-600 capitalize">{{ str_replace('_', ' ', $return->reason) }}</span>
                </div>

                <div>
                    <span class="text-xs text-gray-400 block uppercase font-medium mb-1">Penjelasan Pelanggan:</span>
                    <p class="text-xs text-[#363230] p-3.5 bg-gray-50 rounded-xl border border-gray-200/60 leading-relaxed">
                        {{ $return->customer_notes }}
                    </p>
                </div>

                @if(!empty($return->proof_images))
                <div>
                    <span class="text-xs text-gray-400 block uppercase font-medium mb-2">Foto / Bukti Kerusakan:</span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($return->proof_images as $img)
                        <a href="{{ asset('storage/' . $img) }}" target="_blank" class="block aspect-video rounded-xl overflow-hidden border border-gray-200 hover:opacity-90 transition">
                            <img src="{{ asset('storage/' . $img) }}" alt="Bukti retur" class="w-full h-full object-cover">
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Right 1 col: Processing Form --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-gray-100">
                    <iconify-icon icon="solar:pen-new-square-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    Proses Status & Mutasi Stok
                </h3>

                <form method="POST" action="{{ route('admin.returns.process', $return) }}" class="space-y-4">
                    @csrf

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-xs font-bold text-[#363230] uppercase mb-1">Status Retur</label>
                        <select id="status" name="status" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-[#363230] font-semibold focus:outline-none focus:border-[#DF5E1D]">
                            <option value="pending" @selected($return->status === 'pending')>1. Pending (Menunggu Review)</option>
                            <option value="approved" @selected($return->status === 'approved')>2. Approved (Disetujui Kirim Unit)</option>
                            <option value="item_received" @selected($return->status === 'item_received')>3. Item Received (Unit Tiba di Toko)</option>
                            <option value="completed" @selected($return->status === 'completed')>4. Completed (Selesai Tuntas)</option>
                            <option value="rejected" @selected($return->status === 'rejected')>5. Rejected (Ditolak)</option>
                            <option value="cancelled" @selected($return->status === 'cancelled')>6. Cancelled (Dibatalkan)</option>
                        </select>
                    </div>

                    {{-- Resolution Type --}}
                    <div>
                        <label for="resolution_type" class="block text-xs font-bold text-[#363230] uppercase mb-1">Bentuk Resolusi</label>
                        <select id="resolution_type" name="resolution_type" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-[#363230] focus:outline-none focus:border-[#DF5E1D]">
                            <option value="replacement" @selected($return->resolution_type === 'replacement')>Penggantian Unit Baru</option>
                            <option value="repair" @selected($return->resolution_type === 'repair')>Perbaikan Service Teknisi</option>
                            <option value="refund" @selected($return->resolution_type === 'refund')>Pengembalian Dana (Refund)</option>
                        </select>
                    </div>

                    {{-- Stock Action --}}
                    <div>
                        <label for="stock_action" class="block text-xs font-bold text-[#363230] uppercase mb-1">Tindakan Stok Fisik</label>
                        <select id="stock_action" name="stock_action" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-[#363230] focus:outline-none focus:border-[#DF5E1D]">
                            <option value="return_to_quarantine_qc" @selected($return->stock_action === 'return_to_quarantine_qc')>Masuk Karantina QC Ulang (Belum dijual)</option>
                            <option value="return_to_stock" @selected($return->stock_action === 'return_to_stock')>Kembalikan Langsung ke Stok Jual</option>
                            <option value="scrap_defective" @selected($return->stock_action === 'scrap_defective')>Scrap / Barang Rusak Total (No Stok)</option>
                            <option value="no_stock_change" @selected($return->stock_action === 'no_stock_change')>Tidak Ada Perubahan Stok</option>
                        </select>
                    </div>

                    {{-- Admin Notes --}}
                    <div>
                        <label for="admin_notes" class="block text-xs font-bold text-[#363230] uppercase mb-1">Catatan Admin / Teknisi</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-xs text-[#363230] focus:outline-none focus:border-[#DF5E1D]"
                            placeholder="Catatan hasil inspeksi teknisi atau konfirmasi nomor resi unit pengganti...">{{ $return->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-[#DF5E1D] hover:bg-[#c45218] text-white font-bold rounded-xl text-xs shadow-sm transition-colors flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:check-circle-linear" class="text-base"></iconify-icon>
                        <span>Simpan & Update Status Retur</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
