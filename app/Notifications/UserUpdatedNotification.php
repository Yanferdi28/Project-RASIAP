<?php

namespace App\Notifications;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class UserUpdatedNotification extends BaseNotification
{
    public function __construct(
        protected User $user
    ) {
    }

    protected function buildNotification(): Notification
    {
        return Notification::make()
            ->title('Pengguna Diperbarui')
            ->body('Pengguna ' . $this->user->name . ' (' . $this->user->email . ') telah diperbarui.')
            ->icon('heroicon-o-pencil')
            ->color('warning')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(route('filament.admin.resources.users.edit', $this->user->id))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }
}
