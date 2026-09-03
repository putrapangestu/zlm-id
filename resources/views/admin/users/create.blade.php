@extends('layouts.admin')

@section('title', 'Tambah Pengguna — ZLM.ID Admin')
@section('heading', 'Tambah Pengguna')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('admin.users.index') }}" class="hover:text-[#DF5E1D] transition-colors">Pengguna</a>
        <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
        <span class="text-[#363230] font-medium">Tambah Baru</span>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8">
        <div class="mb-6 pb-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-[#363230]">Buat Akun Pengguna Baru</h2>
            <p class="text-xs text-gray-500 mt-1">Daftarkan akun Admin, Karyawan, atau Pelanggan serta tentukan hak akses operasionalnya.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-[#363230] mb-1.5">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all @error('name') border-red-300 @enderror"
                        placeholder="Contoh: Budi Santoso">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-[#363230] mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all @error('email') border-red-300 @enderror"
                        placeholder="karyawan@zlm.id">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-[#363230] mb-1.5">Nomor WhatsApp / HP</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                        placeholder="081234567890">
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-[#363230] mb-1.5">Role Pengguna</label>
                    <div class="relative">
                        <select id="role" name="role" required onchange="handleRoleChange(this.value)"
                            class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all cursor-pointer @error('role') border-red-300 @enderror">
                            <option value="">Pilih Role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role', 'karyawan') === $role->name)>
                                    {{ ucfirst($role->name) }} @if($role->name === 'admin')(Akses Penuh)@elseif($role->name === 'karyawan')(Hak Akses Dapat Diatur)@endif
                                </option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" style="stroke-width: 1.5;"></iconify-icon>
                    </div>
                    @error('role')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-[#363230] mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all @error('password') border-red-300 @enderror"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-[#363230] mb-1.5">Ulangi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                        placeholder="Ulangi password">
                </div>
            </div>

            {{-- Granular Permissions Section --}}
            <div id="permissions-section" class="pt-6 border-t border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-[#363230] flex items-center gap-2">
                            <iconify-icon icon="solar:shield-check-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                            Atur Hak Akses Karyawan (Permissions)
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Pilih menu dan fitur yang diizinkan untuk akun karyawan ini.</p>
                    </div>
                    <button type="button" onclick="toggleAllPermissions()" class="text-xs font-semibold text-[#DF5E1D] hover:underline">
                        Pilih / Batal Semua
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($permissionGroups as $groupName => $permissions)
                        <div class="bg-gray-50/70 border border-gray-200/70 rounded-xl p-4">
                            <div class="flex items-center justify-between pb-2 mb-3 border-b border-gray-200/50">
                                <span class="text-xs font-bold text-[#363230] uppercase tracking-wider">{{ $groupName }}</span>
                                <button type="button" onclick="toggleGroup('{{ Str::slug($groupName) }}')" class="text-[11px] text-gray-500 hover:text-[#DF5E1D]">
                                    Semua
                                </button>
                            </div>
                            <div class="space-y-2.5 group-container-{{ Str::slug($groupName) }}">
                                @foreach($permissions as $permName => $permLabel)
                                    <label class="flex items-start gap-2.5 cursor-pointer text-xs text-[#363230] hover:text-black">
                                        <input type="checkbox" name="permissions[]" value="{{ $permName }}"
                                            class="perm-checkbox rounded border-gray-300 text-[#DF5E1D] focus:ring-[#DF5E1D]/20 mt-0.5"
                                            @checked(is_array(old('permissions')) && in_array($permName, old('permissions')))>
                                        <span>{{ $permLabel }} <code class="text-[10px] text-gray-400">({{ $permName }})</code></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="admin-notice" class="hidden p-4 rounded-xl bg-orange-50/50 border border-orange-200/60 text-xs text-[#DF5E1D] flex items-center gap-2">
                <iconify-icon icon="solar:info-circle-linear" class="text-base shrink-0"></iconify-icon>
                <span>Akun dengan role <strong>Admin</strong> otomatis memiliki akses penuh tanpa batas ke semua modul sistem.</span>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-medium text-white bg-[#DF5E1D] hover:bg-[#c45218] transition-colors shadow-sm flex items-center gap-2">
                    <iconify-icon icon="solar:check-circle-linear"></iconify-icon>
                    <span>Simpan Pengguna</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function handleRoleChange(role) {
    const permSec = document.getElementById('permissions-section');
    const adminNotice = document.getElementById('admin-notice');
    if (role === 'karyawan') {
        permSec.classList.remove('hidden');
        adminNotice.classList.add('hidden');
    } else if (role === 'admin') {
        permSec.classList.add('hidden');
        adminNotice.classList.remove('hidden');
    } else {
        permSec.classList.add('hidden');
        adminNotice.classList.add('hidden');
    }
}

function toggleAllPermissions() {
    const checkboxes = document.querySelectorAll('.perm-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}

function toggleGroup(groupSlug) {
    const container = document.querySelector('.group-container-' + groupSlug);
    if (!container) return;
    const checkboxes = container.querySelectorAll('.perm-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}

document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    if (roleSelect) handleRoleChange(roleSelect.value);
});
</script>
@endpush
@endsection
