<?php

namespace App\Notifications;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class UserCreatedNotification extends BaseNotification
{
    public function __construct(
        protected User $user
    ) {
    }

    protected function buildNotification(): Notification
    {
        return Notification::make()
            ->title('Pengguna Baru Dibuat')
            ->body('Pengguna ' . $this->user->name . ' (' . $this->user->email . ') telah ditambahkan ke sistem.')
            ->icon('heroicon-o-user-plus')
            ->color('info')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(route('filament.admin.resources.users.edit', $this->user->id))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }
}
