<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Notifications\ReviewRepliedNotification;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with('user', 'product')->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Ulasan berhasil dihapus.');
    }

    /**
     * Store a reply for a review.
     */
    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $review->update([
            'reply' => $request->input('reply'),
            'replied_at' => now(),
        ]);

        // Notify the user who wrote the review
        $review->user->notify(new ReviewRepliedNotification($review));

        return redirect()->route('admin.reviews.index')->with('success', 'Balasan berhasil dikirim.');
    }
}