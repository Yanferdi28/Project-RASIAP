<?php

namespace App\Notifications;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as LaravelNotification;

class UserRejectedNotification extends LaravelNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected User $user)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Akun Anda Ditolak')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Maaf, akun Anda telah ditolak oleh administrator.')
            ->line('Jika Anda pikir ini adalah kesalahan, silakan hubungi administrator sistem.')
            ->line('Terima kasih atas minat Anda menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return Notification::make()
            ->title('Akun Anda Ditolak')
            ->body('Maaf, akun Anda telah ditolak oleh administrator. Jika Anda pikir ini adalah kesalahan, silakan hubungi administrator sistem.')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->getDatabaseMessage();
    }
}
