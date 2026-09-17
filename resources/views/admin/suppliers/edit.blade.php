@extends('layouts.admin')

@section('title', 'Edit Supplier — ZLM.ID Admin')
@section('heading', 'Edit Data Supplier')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-2 text-sm text-gray-400">
        <a href="{{ route('admin.suppliers.index') }}" class="hover:text-[#DF5E1D] transition-colors">Master Supplier</a>
        <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
        <span class="text-[#363230] font-medium">Edit: {{ $supplier->name }}</span>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                    Nama Supplier / Toko Distributor <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $supplier->name) }}" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                        No. Telepon / WhatsApp
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="contact_person" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                        Nama Kontak (PIC)
                    </label>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                    @error('contact_person') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                    Email Supplier (Opsional)
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $supplier->email) }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                    Alamat Lengkap / Gudang Supplier
                </label>
                <textarea id="address" name="address" rows="3"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">{{ old('address', $supplier->address) }}</textarea>
                @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="notes" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                    Catatan Kerjasama / Term Pembayaran
                </label>
                <textarea id="notes" name="notes" rows="2"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">{{ old('notes', $supplier->notes) }}</textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $supplier->is_active))
                    class="rounded text-[#DF5E1D] focus:ring-[#DF5E1D]/20">
                <label for="is_active" class="text-xs font-medium text-gray-700">
                    Aktifkan supplier ini (dapat langsung dipilih saat transaksi restock barang)
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold shadow-sm transition-all flex items-center gap-2">
                    <iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon>
                    <span>Perbarui Supplier</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
