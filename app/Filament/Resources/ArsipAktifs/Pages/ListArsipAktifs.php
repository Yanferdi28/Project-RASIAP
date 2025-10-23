<?php

namespace App\Filament\Resources\ArsipAktifs\Pages;

use App\Filament\Resources\ArsipAktifs\ArsipAktifResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\ImportAction;
use App\Filament\Imports\ArsipAktifImporter;

class ListArsipAktifs extends ListRecords
{
    protected static string $resource = ArsipAktifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Tambah Arsip Aktif')
            ->icon('heroicon-o-document-plus')
            ->color('secondary'),

            ImportAction::make()
                    ->label('Impor Arsip Aktif')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->importer(ArsipAktifImporter::class)
                    ->color('danger')
                    ->fileRules(['mimes:xls,xlsx,csv']),
        ];
    }
}
