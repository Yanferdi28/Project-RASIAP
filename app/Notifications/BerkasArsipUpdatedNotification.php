<?php

namespace App\Notifications;

use App\Models\BerkasArsip;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class BerkasArsipUpdatedNotification extends BaseNotification
{
    public function __construct(
        protected BerkasArsip $berkasArsip
    ) {
    }

    protected function buildNotification(): Notification
    {
        return Notification::make()
            ->title('Berkas Arsip Diperbarui')
            ->body('Berkas Arsip ' . $this->berkasArsip->nama_berkas . ' telah diperbarui.')
            ->icon('heroicon-o-pencil')
            ->color('warning')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(route('filament.admin.resources.berkas-arsips.edit', $this->berkasArsip->nomor_berkas))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }
}
