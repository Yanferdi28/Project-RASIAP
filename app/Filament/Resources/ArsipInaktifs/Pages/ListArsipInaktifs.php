<?php

namespace App\Filament\Resources\ArsipInaktifs\Pages;

use App\Filament\Resources\ArsipInaktifs\ArsipInaktifResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArsipInaktifs extends ListRecords
{
    protected static string $resource = ArsipInaktifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Tambah Arsip Inaktif')
            ->color('secondary'),
        ];
    }
}
