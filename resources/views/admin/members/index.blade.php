@extends('layouts.admin')

@section('title', 'Manajemen Member & Loyalitas — ZLM.ID Admin')
@section('heading', 'Manajemen Member & Loyalitas')

@section('content')
<div class="space-y-6">

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-[#363230]">Manajemen Member & Program Loyalitas</h2>
            <p class="text-xs text-gray-500">Kelola pelanggan loyal, poin cashback, dan status tindak lanjut follow-up CRM.</p>
        </div>
        @can('members.manage')
            <a href="{{ route('admin.members.create') }}" class="px-4 py-2.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2 self-start sm:self-auto">
                <iconify-icon icon="solar:user-plus-bold" class="text-base"></iconify-icon>
                <span>+ Tambah Member Baru</span>
            </a>
        @endcan
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200/60 p-4 shadow-sm">
            <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Total Member</span>
            <p class="text-2xl font-bold text-[#363230] mt-1">{{ $stats['total_members'] }} <span class="text-xs font-normal text-gray-400">User</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 p-4 shadow-sm">
            <span class="text-[11px] font-semibold text-[#DF5E1D] uppercase tracking-wider">Total Poin</span>
            <p class="text-2xl font-bold text-[#DF5E1D] mt-1">{{ number_format($stats['total_points']) }} <span class="text-xs font-normal text-gray-400">Pts</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 p-4 shadow-sm">
            <span class="text-[11px] font-semibold text-purple-600 uppercase tracking-wider">Platinum</span>
            <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['platinum_count'] }} <span class="text-xs font-normal text-gray-400">VIP</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 p-4 shadow-sm">
            <span class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider">Gold</span>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['gold_count'] }} <span class="text-xs font-normal text-gray-400">User</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-amber-200/80 bg-amber-50/40 p-4 shadow-sm col-span-2 sm:col-span-1">
            <span class="text-[11px] font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1">
                <iconify-icon icon="solar:chat-round-call-bold"></iconify-icon>
                <span>Butuh Follow Up</span>
            </span>
            <p class="text-2xl font-bold text-amber-700 mt-1">{{ $stats['needs_follow_up_count'] ?? 0 }} <span class="text-xs font-normal text-amber-800">Target</span></p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></iconify-icon>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, No. HP, No. Member..."
                    class="w-full bg-gray-50 border border-gray-200 text-xs rounded-xl py-2.5 pl-9 pr-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
            </div>
            <div class="relative">
                <select name="tier" class="appearance-none bg-gray-50 border border-gray-200 text-xs text-[#363230] rounded-xl py-2.5 pl-4 pr-9 focus:outline-none focus:border-[#DF5E1D]/30 cursor-pointer">
                    <option value="">Semua Tier</option>
                    <option value="platinum" @selected(request('tier') === 'platinum')>Platinum (5% Diskon)</option>
                    <option value="gold" @selected(request('tier') === 'gold')>Gold (3% Diskon)</option>
                    <option value="silver" @selected(request('tier') === 'silver')>Silver (1.5% Diskon)</option>
                    <option value="bronze" @selected(request('tier') === 'bronze')>Bronze (0%)</option>
                </select>
                <iconify-icon icon="solar:alt-arrow-down-linear" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></iconify-icon>
            </div>
            <div class="relative">
                <select name="follow_up" class="appearance-none bg-gray-50 border border-gray-200 text-xs text-[#363230] rounded-xl py-2.5 pl-4 pr-9 focus:outline-none focus:border-[#DF5E1D]/30 cursor-pointer">
                    <option value="">Semua Status CRM</option>
                    <option value="1" @selected(request('follow_up') === '1')>Butuh Follow-Up</option>
                    <option value="0" @selected(request('follow_up') === '0')>Sudah / Tidak Perlu</option>
                </select>
                <iconify-icon icon="solar:alt-arrow-down-linear" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></iconify-icon>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-medium transition-colors">
                Filter
            </button>
        </form>
    </div>

    {{-- Members Table --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-5 text-[10px] font-medium text-gray-400 uppercase tracking-widest">No. Member</th>
                        <th class="py-4 px-5 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Nama Pelanggan</th>
                        <th class="py-4 px-5 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Kontak (WA)</th>
                        <th class="py-4 px-4 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Tier Member</th>
                        <th class="py-4 px-4 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-center">Poin</th>
                        <th class="py-4 px-4 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-center">Status Follow Up</th>
                        <th class="py-4 px-5 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($members as $member)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        {{-- Member Number --}}
                        <td class="py-4 px-6 font-mono font-bold text-xs text-[#363230]">
                            {{ $member->member_number ?? 'MBR-' . strtoupper(substr($member->id, 0, 6)) }}
                        </td>

                        {{-- Name --}}
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 text-[#DF5E1D] font-bold text-xs flex items-center justify-center">
                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                </div>
                                <span class="font-medium text-[#363230]">{{ $member->name }}</span>
                            </div>
                        </td>

                        {{-- Contact --}}
                        <td class="py-4 px-6">
                            <span class="text-xs text-[#363230] block">{{ $member->email }}</span>
                            <span class="text-[11px] text-gray-400">{{ $member->phone_number ?? '-' }}</span>
                        </td>

                        {{-- Tier --}}
                        <td class="py-4 px-6">
                            @php
                                $tier = strtolower($member->member_tier ?? 'bronze');
                                $tierClass = match($tier) {
                                    'platinum' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'gold' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'silver' => 'bg-slate-100 text-slate-700 border-slate-300',
                                    default => 'bg-orange-50 text-orange-700 border-orange-200',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase border {{ $tierClass }}">
                                <iconify-icon icon="solar:crown-star-bold"></iconify-icon>
                                {{ $tier }} ({{ $member->tier_discount_percentage }}%)
                            </span>
                        </td>

                        {{-- Points --}}
                        <td class="py-4 px-4 text-center">
                            <span class="font-mono font-bold text-xs text-[#DF5E1D] bg-orange-50 px-2.5 py-1 rounded-lg">
                                {{ number_format($member->member_points) }} Pts
                            </span>
                        </td>

                        {{-- Status Follow Up --}}
                        <td class="py-4 px-4 text-center">
                            @if($member->needs_follow_up)
                                <div class="inline-flex flex-col items-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                        <iconify-icon icon="solar:bell-bing-bold" class="text-amber-600"></iconify-icon>
                                        Butuh Follow-Up
                                    </span>
                                    @if($member->follow_up_notes)
                                        <span class="text-[10px] text-gray-500 max-w-[150px] truncate block mt-0.5" title="{{ $member->follow_up_notes }}">
                                            {{ $member->follow_up_notes }}
                                        </span>
                                    @endif
                                    @can('members.manage')
                                        <form action="{{ route('admin.members.follow-up', $member) }}" method="POST" class="mt-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="needs_follow_up" value="0">
                                            <button type="submit" class="text-[10px] text-emerald-600 hover:text-emerald-700 font-bold hover:underline">
                                                &#10003; Tandai Selesai
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            @else
                                <div class="inline-flex flex-col items-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-500">
                                        Normal / Aman
                                    </span>
                                    @can('members.manage')
                                        <form action="{{ route('admin.members.follow-up', $member) }}" method="POST" class="mt-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="needs_follow_up" value="1">
                                            <button type="submit" class="text-[10px] text-amber-600 hover:text-amber-700 font-bold hover:underline">
                                                + Butuh Follow-Up
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="py-4 px-5 text-right">
                            <a href="{{ route('admin.members.show', $member) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-medium transition-colors">
                                <iconify-icon icon="solar:card-2-linear"></iconify-icon>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-gray-400">
                            <p class="text-sm">Tidak ada data member.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $members->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
