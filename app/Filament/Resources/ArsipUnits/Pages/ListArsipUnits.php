<?php

namespace App\Filament\Resources\ArsipUnits\Pages;

use App\Actions\ImportArsipUnitAction;
use App\Filament\Resources\ArsipUnits\ArsipUnitResource;
use App\Exports\ArsipUnitImportTemplate;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListArsipUnits extends ListRecords
{
    protected static string $resource = ArsipUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download_template')
                ->label('Unduh Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->action(function () {
                    return Excel::download(new ArsipUnitImportTemplate(), 'template-arsip-unit.xlsx');
                }),
            
            ImportArsipUnitAction::make(),

            Actions\CreateAction::make()
                ->label('Tambah Arsip Unit')
                ->icon('heroicon-o-document-plus')
                ->color('secondary'),
        ];
    }
}
