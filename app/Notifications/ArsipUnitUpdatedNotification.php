<?php

namespace App\Notifications;

use App\Models\ArsipUnit;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ArsipUnitUpdatedNotification extends BaseNotification
{
    public function __construct(
        protected ArsipUnit $arsipUnit
    ) {
    }

    protected function buildNotification(): Notification
    {
        $description = $this->arsipUnit->uraian_informasi ?? 'Arsip Unit ID: ' . $this->arsipUnit->id_berkas;

        return Notification::make()
            ->title('Arsip Unit Diperbarui')
            ->body('Arsip Unit ' . $description . ' telah diperbarui.')
            ->icon('heroicon-o-pencil')
            ->color('warning')
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(route('filament.admin.resources.arsip-units.edit', $this->arsipUnit->id_berkas))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }
}
