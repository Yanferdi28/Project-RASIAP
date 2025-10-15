<?php

namespace App\Filament\Resources\KodeKlasifikasis\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KodeKlasifikasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_klasifikasi')
                    ->required(),
                TextInput::make('kode_klasifikasi_induk'),
                TextInput::make('uraian')
                    ->required(),
                TextInput::make('retensi_aktif')
                    ->required()
                    ->numeric(),
                TextInput::make('retensi_inaktif')
                    ->required()
                    ->numeric(),
                Select::make('status_akhir')
                    ->options(['Musnah' => 'Musnah', 'Permanen' => 'Permanen', 'Dinilai Kembali' => 'Dinilai kembali'])
                    ->default('Dinilai Kembali')
                    ->required(),
                Select::make('klasifikasi_keamanan')
                    ->options(['Biasa' => 'Biasa', 'Rahasia' => 'Rahasia', 'Terbatas' => 'Terbatas'])
                    ->default('Biasa')
                    ->required(),
            ]);
    }
}
