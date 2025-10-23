<?php

namespace App\Filament\Resources\ArsipUnits\Pages;

use App\Filament\Resources\ArsipUnits\ArsipUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArsipUnits extends ListRecords
{
    protected static string $resource = ArsipUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Arsip Unit')
                ->icon('heroicon-o-document-plus')
                ->color('secondary'),
        ];
    }
}
