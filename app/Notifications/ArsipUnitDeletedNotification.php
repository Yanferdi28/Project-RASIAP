<?php

namespace App\Notifications;

use Filament\Notifications\Notification;

class ArsipUnitDeletedNotification extends BaseNotification
{
    protected string $arsipUnitName;

    public function __construct(string $nama_arsip_unit)
    {
        $this->arsipUnitName = $nama_arsip_unit;
    }

    protected function buildNotification(): Notification
    {
        return Notification::make()
            ->title('Arsip Unit Dihapus')
            ->body('Arsip Unit ' . $this->arsipUnitName . ' telah dihapus.')
            ->icon('heroicon-o-trash')
            ->color('danger');
    }
}
