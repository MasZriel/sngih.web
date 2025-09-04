<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Notifications\NewReviewForAdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review = Review::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewReviewForAdminNotification($review));
        }

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        Review::create([
            'product_id' => $review->product_id,
            'user_id' => Auth::id(),
            'parent_id' => $review->id,
            'comment' => $request->comment,
            // Rating is optional for replies, so we leave it null
        ]);

        return back()->with('success', 'Balasan Anda telah dikirim.');
    }
}