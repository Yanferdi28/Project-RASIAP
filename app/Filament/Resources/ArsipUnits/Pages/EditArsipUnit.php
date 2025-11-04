<?php

namespace App\Filament\Resources\ArsipUnits\Pages;

use App\Filament\Resources\ArsipUnits\ArsipUnitResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditArsipUnit extends EditRecord
{
    protected static string $resource = ArsipUnitResource::class;

    public function mount($record): void
    {
        parent::mount($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
