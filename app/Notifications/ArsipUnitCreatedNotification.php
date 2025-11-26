<?php

namespace App\Notifications;

use App\Models\ArsipUnit;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ArsipUnitCreatedNotification extends BaseNotification
{
    public function __construct(
        protected ArsipUnit $arsipUnit
    ) {
    }

    protected function buildNotification(): Notification
    {
        $description = $this->arsipUnit->uraian_informasi ?? 'Arsip Unit ID: ' . $this->arsipUnit->id_berkas;

        return Notification::make()
            ->title('Arsip Unit Baru Dibuat')
            ->body('Arsip Unit ' . $description . ' telah ditambahkan ke sistem.')
            ->icon('heroicon-o-archive-box')
            ->color('info')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(route('filament.admin.resources.arsip-units.edit', $this->arsipUnit->id_berkas))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }
}
