<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewReviewForAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $review;

    /**
     * Create a new notification instance.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Ulasan baru diterima dari ' . $this->review->user->name,
            'review_id' => $this->review->id,
            'product_name' => $this->review->product->name,
            'rating' => $this->review->rating,
            'url' => route('admin.reviews.index'),
            'icon' => 'fas fa-star',
        ];
    }
}