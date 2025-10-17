<?php

namespace App\Filament\Resources\ArsipAktifs\Pages;

use App\Filament\Resources\ArsipAktifs\ArsipAktifResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArsipAktifs extends ListRecords
{
    protected static string $resource = ArsipAktifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Tambah Arsip Aktif'),
        ];
    }
}
