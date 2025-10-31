<?php

namespace App\Filament\Resources\Subkategoris\Pages;

use App\Filament\Resources\Subkategoris\SubkategoriResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubkategori extends EditRecord
{
    protected static string $resource = SubkategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
