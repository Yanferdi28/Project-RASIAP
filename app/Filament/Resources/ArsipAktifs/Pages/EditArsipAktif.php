<?php

namespace App\Filament\Resources\ArsipAktifs\Pages;

use App\Filament\Resources\ArsipAktifs\ArsipAktifResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditArsipAktif extends EditRecord
{
    protected static string $resource = ArsipAktifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
