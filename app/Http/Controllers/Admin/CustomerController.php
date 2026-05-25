<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'active' => $query->whereNotNull('email_verified_at'),
                'inactive' => $query->whereNull('email_verified_at'),
                default => null,
            };
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        $statusFilter = $request->status ?? 'all';

        return view('admin.customers.index', compact('users', 'statusFilter'));
    }

    public function show(User $user): View
    {
        $user->load(['orders.items', 'reviews.laptop']);

        $totalSpending = $user->orders->sum('total');
        $orderCount = $user->orders->count();
        $reviewCount = $user->reviews->count();
        $lastOrderDate = $user->orders->max('created_at');

        $customer = $user;

        return view('admin.customers.show', compact(
            'customer',
            'totalSpending',
            'orderCount',
            'reviewCount',
            'lastOrderDate'
        ));
    }
}
