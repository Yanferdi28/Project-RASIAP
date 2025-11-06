<?php

namespace App\Filament\Resources\Subkategoris\Pages;

use App\Filament\Resources\Subkategoris\SubKategoriResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubKategori extends EditRecord
{
    protected static string $resource = SubKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
