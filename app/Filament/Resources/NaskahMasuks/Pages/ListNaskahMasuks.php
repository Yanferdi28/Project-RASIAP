<?php

namespace App\Filament\Resources\NaskahMasuks\Pages;

use App\Filament\Resources\NaskahMasuks\NaskahMasukResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNaskahMasuks extends ListRecords
{
    protected static string $resource = NaskahMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
