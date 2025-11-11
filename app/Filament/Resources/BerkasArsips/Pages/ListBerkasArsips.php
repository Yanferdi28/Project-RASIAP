<?php

namespace App\Filament\Resources\BerkasArsips\Pages;

use App\Actions\ExportBerkasArsipLaravelExcelAction;
use App\Filament\Resources\BerkasArsips\BerkasArsipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBerkasArsips extends ListRecords
{
    protected static string $resource = BerkasArsipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Tambah Berkas Arsip')
            ->icon('heroicon-o-document-plus')
            ->color('secondary')
            ->visible(auth()->user()->can('create', \App\Models\BerkasArsip::class)),
        ];
    }
}