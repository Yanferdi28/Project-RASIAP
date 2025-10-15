<?php

namespace App\Filament\Resources\ArsipUnits\Pages;

use App\Filament\Resources\ArsipUnits\ArsipUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditArsipUnit extends EditRecord
{
    protected static string $resource = ArsipUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
