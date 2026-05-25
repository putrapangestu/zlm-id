<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Laptop $laptop)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = $laptop->reviews()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'rating' => $data['rating'],
                'comment' => $data['comment'],
                'is_approved' => false,
            ]
        );

        return redirect()->back()->with('success', 'Review submitted for approval.');
    }
}
