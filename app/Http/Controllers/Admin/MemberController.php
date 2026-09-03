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
        ];

        return view('admin.members.index', compact('members', 'stats'));
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
