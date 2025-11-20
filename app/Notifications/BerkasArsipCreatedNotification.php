<?php

namespace App\Notifications;

use App\Models\BerkasArsip;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class BerkasArsipCreatedNotification extends BaseNotification
{
    public function __construct(
        protected BerkasArsip $berkasArsip
    ) {
    }

    protected function buildNotification(): Notification
    {
        return Notification::make()
            ->title('Berkas Arsip Baru Dibuat')
            ->body('Berkas Arsip ' . $this->berkasArsip->nama_berkas . ' telah ditambahkan ke sistem.')
            ->icon('heroicon-o-document-plus')
            ->color('info')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(route('filament.admin.resources.berkas-arsips.edit', $this->berkasArsip->id))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }
}
