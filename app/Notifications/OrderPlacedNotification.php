<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Pesanan Diterima')
                    ->greeting('Halo ' . $notifiable->name . ',')
                    ->line('Terima kasih telah berbelanja di Snagih!')
                    ->line('Pesanan Anda dengan ID #' . $this->order->id . ' telah kami terima dan akan segera diproses.')
                    ->action('Lihat Detail Pesanan', route('orders.show', $this->order->id))
                    ->line('Kami akan memberitahu Anda lagi setelah pesanan Anda dikirim.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Pesanan Anda #' . $this->order->id . ' telah berhasil dibuat.',
            'url' => route('orders.show', $this->order->id),
        ];
    }
}
