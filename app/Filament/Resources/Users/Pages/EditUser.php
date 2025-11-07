<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Actions\RejectUserAction;
use App\Filament\Actions\VerifyUserAction;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        $user = $this->getRecord();
        
        $actions = [
            DeleteAction::make()->label('Hapus'),
        ];

        // Add verification actions only if the user is pending verification
        if ($user->verification_status === 'pending') {
            $actions[] = VerifyUserAction::make();
            $actions[] = RejectUserAction::make();
        }

        return $actions;
    }
}
