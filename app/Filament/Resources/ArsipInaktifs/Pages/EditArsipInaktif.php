<?php

namespace App\Filament\Resources\ArsipInaktifs\Pages;

use App\Filament\Resources\ArsipInaktifs\ArsipInaktifResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditArsipInaktif extends EditRecord
{
    protected static string $resource = ArsipInaktifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
