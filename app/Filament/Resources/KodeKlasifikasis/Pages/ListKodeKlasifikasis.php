<?php

namespace App\Filament\Resources\KodeKlasifikasis\Pages;

use App\Filament\Resources\KodeKlasifikasis\KodeKlasifikasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKodeKlasifikasis extends ListRecords
{
    protected static string $resource = KodeKlasifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Kode Klasifikasi')
                ->icon('heroicon-o-document-plus')
                ->color('secondary'),
        ];
    }
}
