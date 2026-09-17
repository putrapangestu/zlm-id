<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::role('customer')->withCount('orders');

        if ($tier = $request->get('tier')) {
            $query->where('member_tier', $tier);
        }

        if ($request->filled('follow_up')) {
            $query->where('needs_follow_up', $request->get('follow_up') === '1');
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_members' => User::role('customer')->count(),
            'total_points' => User::role('customer')->sum('member_points'),
            'platinum_count' => User::role('customer')->where('member_tier', 'platinum')->count(),
            'gold_count' => User::role('customer')->where('member_tier', 'gold')->count(),
            'needs_follow_up_count' => User::role('customer')->where('needs_follow_up', true)->count(),
        ];

        return view('admin.members.index', compact('members', 'stats'));
    }

    public function create(): View
    {
        return view('admin.members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'member_tier' => 'required|in:bronze,silver,gold,platinum',
            'member_points' => 'nullable|integer|min:0',
            'needs_follow_up' => 'nullable|boolean',
            'follow_up_notes' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6',
        ]);

        $memberNumber = 'MBR-' . date('ym') . '-' . strtoupper(\Illuminate\Support\Str::random(4));
        while (User::where('member_number', $memberNumber)->exists()) {
            $memberNumber = 'MBR-' . date('ym') . '-' . strtoupper(\Illuminate\Support\Str::random(4));
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password'] ?? 'zlm12345'),
            'member_number' => $memberNumber,
            'member_tier' => $validated['member_tier'],
            'member_points' => $validated['member_points'] ?? 0,
            'needs_follow_up' => $request->boolean('needs_follow_up'),
            'follow_up_notes' => $validated['follow_up_notes'] ?? null,
            'follow_up_date' => $request->boolean('needs_follow_up') ? now() : null,
        ]);

        $user->assignRole('customer');

        return redirect()->route('admin.members.index')
            ->with('success', "Member {$user->name} berhasil ditambahkan dengan nomor {$user->member_number}.");
    }

    public function toggleFollowUp(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'needs_follow_up' => 'required|boolean',
            'follow_up_notes' => 'nullable|string|max:500',
        ]);

        $user->update([
            'needs_follow_up' => $request->boolean('needs_follow_up'),
            'follow_up_notes' => $validated['follow_up_notes'] ?? $user->follow_up_notes,
            'follow_up_date' => $request->boolean('needs_follow_up') ? now() : null,
        ]);

        $statusText = $request->boolean('needs_follow_up') ? 'ditandai butuh follow-up' : 'ditandai sudah di-follow up';
        return redirect()->back()->with('success', "Member {$user->name} berhasil {$statusText}.");
    }

    public function show(User $user): View
    {
        $orders = $user->orders()->with('items.laptop')->latest()->paginate(10);
        $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('total');

        return view('admin.members.show', compact('user', 'orders', 'totalSpent'));
    }

    public function adjustPoints(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:add,deduct',
            'points' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        if ($validated['type'] === 'add') {
            $user->increment('member_points', $validated['points']);
        } else {
            $user->decrement('member_points', min($user->member_points, $validated['points']));
        }

        return redirect()->back()
            ->with('success', "Poin member {$user->name} berhasil diperbarui.");
    }
}
