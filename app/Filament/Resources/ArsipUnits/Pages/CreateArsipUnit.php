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
        

        if ($user && $user->hasRole('operator')) {
            abort(403, 'You do not have permission to create archive units.');
        }
        
        parent::mount();
    }
}
