<?php

namespace App\Filament\Resources\ArsipAktifs\Pages;

use App\Filament\Resources\ArsipAktifs\ArsipAktifResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewArsipAktif extends ViewRecord
{
    protected static string $resource = ArsipAktifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Berkas Arsip')
                ->modalDescription('Apakah Anda yakin ingin menghapus berkas ini?'),
        ];
    }
}