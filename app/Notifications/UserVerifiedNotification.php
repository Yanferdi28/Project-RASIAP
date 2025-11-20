<?php

namespace App\Notifications;

use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as LaravelNotification;

class UserVerifiedNotification extends LaravelNotification
{
    use Queueable;

    public function __construct(protected User $user)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

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

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Akun Anda Telah Diverifikasi')
            ->body('Selamat! Akun Anda telah diverifikasi oleh administrator. Anda sekarang dapat login ke sistem.')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->getDatabaseMessage();
    }
}
