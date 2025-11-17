<?php

namespace App\Filament\Resources\ArsipUnits\Pages;

use App\Filament\Resources\ArsipUnits\ArsipUnitResource;
use App\Filament\Resources\ArsipUnits\Schemas\ArsipUnitForm;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateArsipUnit extends CreateRecord
{
    protected static string $resource = ArsipUnitResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        // If the user doesn't have admin role, ensure they can't change the unit_pengolah_arsip_id
        if (!$user->hasRole(['admin', 'superadmin']) && $user->unit_pengolah_id) {
            $data['unit_pengolah_arsip_id'] = $user->unit_pengolah_id;
        }

        return $data;
    }

    public function mount(): void
    {
        $user = Auth::user();

        parent::mount();

        // Auto-select the user's unit_pengolah if they have one and is not admin
        if ($user && $user->unit_pengolah_id && !$user->hasRole(['admin', 'superadmin'])) {
            $this->form->fill([
                'unit_pengolah_arsip_id' => $user->unit_pengolah_id,
            ]);
        }
    }
}
