<?php

namespace App\Filament\Resources\ArsipAktifs\Schemas;

use App\Models\KodeKlasifikasi;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ArsipAktifForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('nama_berkas')
                    ->label('Nama Berkas')
                    ->required()
                    ->columnSpanFull(),

                Select::make('klasifikasi_id')
                    ->label('Klasifikasi')
                    ->relationship(name: 'klasifikasi')
                    ->getOptionLabelFromRecordUsing(fn (KodeKlasifikasi $record) => "{$record->kode_klasifikasi} - {$record->uraian}")
                    ->searchable(['kode_klasifikasi', 'uraian'])
                    ->preload()
                    ->live()
                    ->required()
                    ->afterStateUpdated(function (?string $state, callable $set) {
                        if (blank($state)) {
                            $set('retensi_aktif', null);
                            $set('retensi_inaktif', null);
                            $set('penyusutan_akhir', null);
                            return;
                        }

                        $klasifikasi = KodeKlasifikasi::find($state);
                        if ($klasifikasi) {
                            $set('retensi_aktif', $klasifikasi->retensi_aktif);
                            $set('retensi_inaktif', $klasifikasi->retensi_inaktif);
                            $set('penyusutan_akhir', $klasifikasi->status_akhir);
                        }
                    })
                    ->columnSpanFull(),

                TextInput::make('retensi_aktif')
                    ->label('Retensi Aktif')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true),

                TextInput::make('retensi_inaktif')
                    ->label('Retensi Inaktif')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true),

                TextInput::make('penyusutan_akhir')
                    ->label('Penyusutan Akhir')
                    ->disabled()
                    ->dehydrated(true),
                
                TextInput::make('lokasi_fisik')
                    ->label('Lokasi Fisik'),

                Textarea::make('uraian')
                    ->label('Uraian')
                    ->columnSpanFull(),

                Select::make('kategori_berkas')
                    ->label('Kategori Berkas')
                    ->options([
                        'Asli' => 'Asli',
                        'Salinan' => 'Salinan',
                        'Tembusan' => 'Tembusan',
                        'Pertinggal' => 'Pertinggal',
                    ])
                    ->required(),

                TextInput::make('nomor_berkas')
                    ->label('Nomor Berkas')
                    ->disabled()
                    ->visibleOn('edit'),
            ]);
    }
}