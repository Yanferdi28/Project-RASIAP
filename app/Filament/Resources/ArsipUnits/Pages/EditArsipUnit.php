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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();
        $record = $this->getRecord();

        // If the user doesn't have admin role, prevent them from changing the unit_pengolah_arsip_id
        if (!$user->hasRole(['admin', 'superadmin'])) {
            $data['unit_pengolah_arsip_id'] = $record->unit_pengolah_arsip_id;
        }

        return $data;
    }

    public function mount($record): void
    {
        parent::mount($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make()
                ->visible(fn ($record) => Auth::user()->can('delete', $record)),
        ];
    }
}
