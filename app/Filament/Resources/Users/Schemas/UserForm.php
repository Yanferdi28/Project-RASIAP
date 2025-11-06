<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\UnitPengolah;
use Filament\Forms\Components\Select;
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
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                
                Select::make('unit_pengolah_id')
                    ->label('Unit Pengolah')
                    ->relationship('unitPengolah', 'nama_unit')
                    ->placeholder('Pilih Unit Pengolah')
                    ->searchable()
                    ->preload(),
                
                Select::make('roles')
                    ->label('Peran (Roles)')
                    ->relationship('roles', 'name')
                    ->placeholder('Pilih Peran')
                    ->searchable()
                    ->preload()
                    ->multiple(), // Multiple select agar bisa memilih lebih dari satu role
            ]);
    }
}