@extends('layouts.admin')

@section('title', 'Tambah Member Baru — ZLM.ID')
@section('heading', 'Tambah Member & Loyalitas')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400">
        <a href="{{ route('admin.members.index') }}" class="hover:text-[#DF5E1D] transition-colors">Member & Loyalitas</a>
        <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
        <span class="text-[#363230] font-medium">Registrasi Member Baru</span>
    </div>

    <form method="POST" action="{{ route('admin.members.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-5">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-gray-100">
                <iconify-icon icon="solar:user-plus-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                Data Diri Member / Pelanggan
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="budi@example.com"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">No. WhatsApp / Telepon</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="081234567890"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    @error('phone_number') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password Akun (Opsional)</label>
                    <input type="password" name="password" placeholder="Default: zlm12345 (jika dikosongkan)"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    <p class="text-[10px] text-gray-400 mt-1">Digunakan member untuk login ke aplikasi / website.</p>
                </div>
            </div>
        </div>

        {{-- Level Tier & Poin --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-5">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-gray-100">
                <iconify-icon icon="solar:medal-ribbon-star-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                Pengaturan Tier & Poin Loyalitas
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tier / Level Member <span class="text-red-500">*</span></label>
                    <select name="member_tier" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        <option value="bronze" @selected(old('member_tier') === 'bronze')>Bronze Member</option>
                        <option value="silver" @selected(old('member_tier') === 'silver')>Silver Member</option>
                        <option value="gold" @selected(old('member_tier') === 'gold')>Gold Member</option>
                        <option value="platinum" @selected(old('member_tier') === 'platinum')>Platinum Member</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Saldo Poin Awal</label>
                    <input type="number" name="member_points" value="{{ old('member_points', 0) }}" min="0"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold focus:outline-none focus:border-[#DF5E1D]">
                </div>
            </div>
        </div>

        {{-- Status Follow-Up Pelanggan --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-4">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-gray-100">
                <iconify-icon icon="solar:chat-round-call-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                Status Follow Up CRM
            </h3>

            <div class="flex items-center justify-between p-3.5 bg-amber-50/60 rounded-xl border border-amber-200/70">
                <div>
                    <span class="text-xs font-bold text-amber-900 block">Tandai Butuh Follow Up</span>
                    <span class="text-[11px] text-amber-700">Aktifkan jika member ini perlu dihubungi tim sales/CRM (misal menanyakan minat unit atau promo).</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="needs_follow_up" value="1" @checked(old('needs_follow_up')) class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                </label>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Catatan Follow Up</label>
                <textarea name="follow_up_notes" rows="3" placeholder="Contoh: Member menanyakan ketersediaan Thinkpad T14 Gen 2, janji follow-up lusa."
                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:border-[#DF5E1D]">{{ old('follow_up_notes') }}</textarea>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.members.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
                <iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon>
                <span>Simpan Member</span>
            </button>
        </div>
    </form>
</div>
@endsection
