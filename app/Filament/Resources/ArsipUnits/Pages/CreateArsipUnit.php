<?php

namespace App\Filament\Resources\ArsipUnits\Pages;

use App\Filament\Resources\ArsipUnits\ArsipUnitResource;
use App\Filament\Resources\ArsipUnits\Schemas\ArsipUnitForm;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateArsipUnit extends CreateRecord
{
    protected static string $resource = ArsipUnitResource::class;

    public function mount(): void
    {
        $user = Auth::user();
        
        parent::mount();
        
        // Auto-select the user's unit_pengolah if they have one
        if ($user && $user->unit_pengolah_id) {
            $this->form->fill([
                'unit_pengolah_arsip_id' => $user->unit_pengolah_id,
            ]);
        }
    }
}
