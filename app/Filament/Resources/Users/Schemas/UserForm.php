<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    // Enkripsi password saat disimpan
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    // Hanya proses field ini jika diisi (penting untuk halaman edit)
                    ->dehydrated(fn ($state) => filled($state))
                    // Wajib diisi hanya saat membuat user baru
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                
                // Komponen untuk memilih peran/role
                CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    // DIHAPUS: Method ->preload() tidak ada untuk CheckboxList
                    ->label('Peran (Roles)'),
            ]);
    }
}