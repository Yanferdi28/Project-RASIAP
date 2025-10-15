<?php

namespace App\Filament\Resources\KodeKlasifikasis\Pages;

use App\Filament\Resources\KodeKlasifikasis\KodeKlasifikasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKodeKlasifikasi extends EditRecord
{
    protected static string $resource = KodeKlasifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
