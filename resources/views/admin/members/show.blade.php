@extends('layouts.admin')

@section('title', 'Profil Member: ' . $user->name . ' — ZLM.ID Admin')
@section('heading', 'Profil & Kartu Member')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400">
        <a href="{{ route('admin.members.index') }}" class="hover:text-[#DF5E1D] transition-colors">Member</a>
        <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
        <span class="text-[#363230] font-semibold">{{ $user->name }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Digital Member Card (Left Column) --}}
        <div class="space-y-6">
            @php
                $tier = strtolower($user->member_tier ?? 'bronze');
                $cardBg = match($tier) {
                    'platinum' => 'from-slate-900 via-purple-950 to-slate-900 text-white border-purple-500/30',
                    'gold' => 'from-amber-700 via-amber-900 to-yellow-900 text-white border-amber-400/40',
                    'silver' => 'from-slate-700 via-gray-800 to-zinc-900 text-white border-slate-400/30',
                    default => 'from-orange-800 via-orange-950 to-stone-900 text-white border-orange-500/30',
                };
            @endphp

            {{-- Card Visual --}}
            <div class="relative bg-gradient-to-br {{ $cardBg }} rounded-3xl p-6 shadow-xl border overflow-hidden aspect-[1.58/1] flex flex-col justify-between">
                <div class="absolute -right-8 -top-8 w-36 h-36 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('assets/logo.png') }}" alt="" class="h-6 w-6 object-contain brightness-0 invert">
                        <span class="font-bold tracking-tighter text-sm">ZLM<span class="text-[#DF5E1D]">.ID</span> MEMBER</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-widest bg-white/20 backdrop-blur-md">
                        {{ $tier }}
                    </span>
                </div>

                <div class="my-auto py-2">
                    <span class="font-mono text-sm tracking-widest block font-bold">{{ $user->member_number ?? 'MBR-000000' }}</span>
                    <span class="text-xs text-white/80 block mt-1 font-semibold uppercase">{{ $user->name }}</span>
                </div>

                <div class="flex items-end justify-between pt-2 border-t border-white/10 text-xs">
                    <div>
                        <span class="text-[10px] text-white/60 block uppercase">Poin Loyalitas</span>
                        <span class="font-mono font-bold text-sm text-[#DF5E1D]">{{ number_format($user->member_points) }} PTS</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-white/60 block uppercase">Diskon Member</span>
                        <span class="font-bold text-sm">{{ $user->tier_discount_percentage }}% OFF</span>
                    </div>
                </div>
            </div>

            {{-- Point Adjustment Box --}}
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-4">
                <h3 class="text-xs font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-2 border-b border-gray-100">
                    <iconify-icon icon="solar:star-fall-linear" class="text-[#DF5E1D] text-base"></iconify-icon>
                    Penyesuaian Poin Manual
                </h3>

                <form method="POST" action="{{ route('admin.members.points', $user) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Aksi</label>
                        <select name="type" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2 text-xs">
                            <option value="add">Tambah Poin (+)</option>
                            <option value="deduct">Kurangi Poin (-)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Jumlah Poin</label>
                        <input type="number" name="points" min="1" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2 text-xs font-bold font-mono">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Alasan Penyesuaian</label>
                        <input type="text" name="reason" required placeholder="Contoh: Reward Promo / Koreksi transaksi" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2 text-xs">
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white text-xs font-bold rounded-xl transition-colors">
                        Simpan Penyesuaian Poin
                    </button>
                </form>
            </div>
        </div>

        {{-- Transaction History (Right 2 cols) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                            <iconify-icon icon="solar:bill-list-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                            Riwayat Transaksi Member
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Total Belanja Sukses: <strong class="text-emerald-600 font-mono">Rp {{ number_format($totalSpent, 0, ',', '.') }}</strong></p>
                    </div>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/60 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            <th class="py-3 px-6">No. Pesanan</th>
                            <th class="py-3 px-6">Sumber</th>
                            <th class="py-3 px-6">Total Belanja</th>
                            <th class="py-3 px-6">Diskon & Poin</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @forelse($orders as $order)
                        <tr>
                            <td class="py-4 px-6">
                                <span class="font-mono font-bold text-[#363230] block">{{ $order->order_number }}</span>
                                <span class="text-gray-400 text-[11px]">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $order->source === 'pos' ? 'bg-blue-50 text-blue-700' : 'bg-orange-50 text-[#DF5E1D]' }}">
                                    {{ $order->source === 'pos' ? 'Kasir POS' : 'Online Store' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-mono font-bold text-emerald-600">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                @if($order->member_discount_amount > 0)
                                    <span class="text-emerald-600 font-semibold block">-Rp {{ number_format($order->member_discount_amount, 0, ',', '.') }}</span>
                                @endif
                                <span class="text-gray-400 text-[11px]">+{{ $order->points_earned }} Pts didapat</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.transactions.show', $order) }}" class="text-xs font-semibold text-[#DF5E1D] hover:underline">
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">Belum ada transaksi dari member ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
