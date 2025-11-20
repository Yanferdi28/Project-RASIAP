<?php

namespace App\Filament\Actions;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class RejectUserAction
{
    public static function make(): Action
    {
        return Action::make('reject_user')
            ->label('Tolak Pengguna')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Tolak Pengguna')
            ->modalDescription('Apakah Anda yakin ingin menolak pengguna ini?')
            ->modalSubmitActionLabel('Tolak')
            ->action(function (array $data, $record) {
                /** @var User $record */

                // Update user to rejected status
                $record->update([
                    'verification_status' => 'rejected',
                    'verified_at' => now(),
                    'verified_by' => Auth::id(),
                    'verification_notes' => $data['verification_notes'] ?? null,
                ]);

                // Add a success notification
                Notification::make()
                    ->title('Pengguna berhasil ditolak')
                    ->success()
                    ->send();

                // Send notification to user about rejection
                $record->notify(new \App\Notifications\UserRejectedNotification($record));

                // Send notification to admin about rejection
                $verifier = Auth::user();
                $admins = User::role(['admin', 'superadmin'])->get();
                foreach ($admins as $admin) {
                    if ($admin->id !== $verifier->id) { // Don't notify the verifier themselves
                        $admin->notify(new \App\Notifications\AdminUserRejectedNotification($record, $verifier));
                    }
                }
            })
            ->form([
                \Filament\Forms\Components\Textarea::make('verification_notes')
                    ->label('Catatan Penolakan')
                    ->rows(3)
                    ->placeholder('Masukkan alasan penolakan')
                    ->required(),
            ]);
    }
}