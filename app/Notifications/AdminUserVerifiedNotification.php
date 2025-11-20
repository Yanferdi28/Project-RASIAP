<?php

namespace App\Notifications;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class AdminUserVerifiedNotification extends BaseNotification
{
    public function __construct(
        protected User $user,
        protected User $verifier
    ) {
    }

    protected function buildNotification(): Notification
    {
        return Notification::make()
            ->title('Pengguna Berhasil Diverifikasi')
            ->body('Pengguna ' . $this->user->name . ' (' . $this->user->email . ') telah diverifikasi oleh ' . $this->verifier->name . '.')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(route('filament.admin.resources.users.edit', $this->user->id))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }
}
