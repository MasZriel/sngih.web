<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ReviewRepliedNotification extends Notification implements ShouldQueue
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
            'message' => 'Ulasan Anda untuk produk ' . $this->review->product->name . ' telah dibalas oleh penjual.',
            'review_id' => $this->review->id,
            'url' => route('products.show', ['id' => $this->review->product->id]),
            'icon' => 'fas fa-reply',
        ];
    }
}