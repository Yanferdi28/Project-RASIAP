<?php

namespace App\Notifications;

use Filament\Notifications\Notification;

class BerkasArsipDeletedNotification extends BaseNotification
{
    protected string $berkasArsipName;

    public function __construct(string $nama_berkas)
    {
        $this->berkasArsipName = $nama_berkas;
    }

    protected function buildNotification(): Notification
    {
        return Notification::make()
            ->title('Berkas Arsip Dihapus')
            ->body('Berkas Arsip ' . $this->berkasArsipName . ' telah dihapus dari sistem.')
            ->icon('heroicon-o-trash')
            ->color('danger');
    }
}
