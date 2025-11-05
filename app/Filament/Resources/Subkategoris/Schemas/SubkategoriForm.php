<?php

namespace App\Filament\Resources\SubKategoris\Schemas;

use App\Models\Kategori;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SubKategoriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([

                Select::make('kategori_id')
                    ->label('Kategori Utama (Waktu Ketersediaan)')
                    ->relationship('kategori', 'nama_kategori')
                    ->options(Kategori::pluck('nama_kategori', 'id')->toArray())
                    ->required()
                    ->searchable(),
                    

                TextInput::make('nama_sub_kategori')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Item Informasi/Sub Kategori'),
                    
                Textarea::make('deskripsi')
                    ->columnSpanFull()
                    ->label('Keterangan Singkat (Opsional)'),
            ]);
    }
}