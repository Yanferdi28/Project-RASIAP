<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserVerifiedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Akun Anda Telah Diverifikasi')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Akun Anda telah diverifikasi oleh administrator.')
            ->line('Anda sekarang dapat login ke sistem dengan menggunakan kredensial yang Anda daftarkan.')
            ->action('Login ke Sistem', url('/login'))
            ->line('Terima kasih telah menggunakan layanan kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Akun Anda telah berhasil diverifikasi oleh administrator',
            'verified_at' => now(),
        ];
    }
}
