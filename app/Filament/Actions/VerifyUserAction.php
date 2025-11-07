<?php

namespace App\Filament\Actions;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class VerifyUserAction
{
    public static function make(): Action
    {
        return Action::make('verify_user')
            ->label('Verifikasi Pengguna')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Verifikasi Pengguna')
            ->modalDescription('Apakah Anda yakin ingin memverifikasi pengguna ini?')
            ->modalSubmitActionLabel('Verifikasi')
            ->action(function (array $data, $record) {
                /** @var User $record */
                
                // Update user to verified status with roles and unit
                $record->update([
                    'verification_status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => Auth::id(),
                    'verification_notes' => $data['verification_notes'] ?? null,
                    'unit_pengolah_id' => $data['unit_pengolah_id'] ?? null,
                ]);

                // Assign roles to the user
                $record->assignRoles($data['roles'] ?? []);

                // Add a success notification
                Notification::make()
                    ->title('Pengguna berhasil diverifikasi')
                    ->success()
                    ->send();

                // Send notification to user about verification
                $record->notify(new \App\Notifications\UserVerifiedNotification($record));
            })
            ->form([
                \Filament\Forms\Components\Select::make('roles')
                    ->label('Peran (Roles)')
                    ->options(\Spatie\Permission\Models\Role::all()->pluck('name', 'name'))
                    ->placeholder('Pilih Peran')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->required(),
                    
                \Filament\Forms\Components\Select::make('unit_pengolah_id')
                    ->label('Unit Pengolah')
                    ->relationship('unitPengolah', 'nama_unit')
                    ->placeholder('Pilih Unit Pengolah')
                    ->searchable()
                    ->preload(),
                
                \Filament\Forms\Components\Textarea::make('verification_notes')
                    ->label('Catatan Verifikasi')
                    ->rows(3)
                    ->placeholder('Masukkan catatan verifikasi (jika ada)'),
            ]);
    }
}