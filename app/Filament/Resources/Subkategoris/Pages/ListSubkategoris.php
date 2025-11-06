<?php

namespace App\Filament\Resources\Subkategoris\Pages;

use App\Filament\Resources\Subkategoris\SubKategoriResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubKategoris extends ListRecords
{
    protected static string $resource = SubKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Tambah Sub Kategori')
            ->icon('heroicon-o-document-plus')
            ->color('secondary'),
        ];
    }
}
