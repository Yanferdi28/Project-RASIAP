<?php

namespace App\Notifications;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class AdminUserRejectedNotification extends BaseNotification
{
    public function __construct(
        protected User $user,
        protected User $rejector
    ) {
    }

    protected function buildNotification(): Notification
    {
        return Notification::make()
            ->title('Pengguna Ditolak')
            ->body('Pengguna ' . $this->user->name . ' (' . $this->user->email . ') telah ditolak oleh ' . $this->rejector->name . '.')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(route('filament.admin.resources.users.edit', $this->user->id))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }
}
