<?php

namespace App\Filament\Resources\NaskahMasuks\Pages;

use App\Filament\Resources\NaskahMasuks\NaskahMasukResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNaskahMasuk extends EditRecord
{
    protected static string $resource = NaskahMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
