<?php

namespace App\Notifications;

use Filament\Notifications\Notification;

class UserDeletedNotification extends BaseNotification
{
    public function __construct(
        protected string $userName,
        protected string $userEmail
    ) {
    }

    protected function buildNotification(): Notification
    {
        return Notification::make()
            ->title('Pengguna Dihapus')
            ->body('Pengguna ' . $this->userName . ' (' . $this->userEmail . ') telah dihapus dari sistem.')
            ->icon('heroicon-o-trash')
            ->color('danger');
    }
}
