<?php

namespace App\Filament\Resources\Subkategoris\Pages;

use App\Filament\Resources\Subkategoris\SubkategoriResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubkategoris extends ListRecords
{
    protected static string $resource = SubkategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
