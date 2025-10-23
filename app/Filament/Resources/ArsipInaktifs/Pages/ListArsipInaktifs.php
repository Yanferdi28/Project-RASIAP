<?php

namespace App\Filament\Resources\ArsipInaktifs\Pages;

use App\Filament\Resources\ArsipInaktifs\ArsipInaktifResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\ImportAction;
use App\Filament\Imports\ArsipInaktifImporter;

class ListArsipInaktifs extends ListRecords
{
    protected static string $resource = ArsipInaktifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Tambah Arsip Inaktif')
            ->icon('heroicon-o-document-plus')
            ->color('secondary'),

            ImportAction::make()
                    ->label('Impor Arsip Aktif')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->importer(ArsipInaktifImporter::class)
                    ->color('danger'),
        ];
    }
}
