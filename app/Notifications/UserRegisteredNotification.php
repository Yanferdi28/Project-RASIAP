<?php

namespace App\Notifications;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as BaseNotification;

class UserRegisteredNotification extends BaseNotification
{
    use Queueable;

    public function __construct(
        protected User $newUser
    ) {
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
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return Notification::make()
            ->title('Pengguna Baru Mendaftar')
            ->body('Pengguna ' . $this->newUser->name . ' (' . $this->newUser->email . ') telah mendaftar dan menunggu verifikasi akun.')
            ->icon('heroicon-o-bell')
            ->color('info')
            ->actions([
                Action::make('verify')
                    ->label('Verifikasi Sekarang')
                    ->url(route('filament.admin.resources.users.edit', $this->newUser->id))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ])
            ->getDatabaseMessage();
    }
}



