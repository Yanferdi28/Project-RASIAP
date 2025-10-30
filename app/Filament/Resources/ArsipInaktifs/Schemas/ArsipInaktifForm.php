<?php

namespace App\Filament\Resources\ArsipInaktifs\Schemas;

use App\Models\KodeKlasifikasi;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArsipInaktifForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Section 1: Nama Berkas, Klasifikasi, Retensi, Penyusutan
                Section::make('Informasi Utama')
                    ->columns(2)
                    ->schema([
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
                    ]),

                // Section 2: Lokasi Fisik, Uraian, Kategori, Nomor Berkas
                Section::make('Detail Berkas')
                    ->columns(2)
                    ->schema([
                        TextInput::make('lokasi_fisik')
                            ->label('Lokasi Fisik'),

                        Select::make('kategori_berkas')
                            ->label('Kategori Berkas')
                            ->options([
                                'Asli' => 'Asli',
                                'Salinan' => 'Salinan',
                                'Tembusan' => 'Tembusan',
                                'Pertinggal' => 'Pertinggal',
                            ])
                            ->required(),

                        Textarea::make('uraian')
                            ->label('Uraian')
                            ->columnSpanFull(),

                        TextInput::make('nomor_berkas')
                            ->label('Nomor Berkas')
                            ->disabled()
                            ->visibleOn('edit'),
                    ]),
            ]);
    }
}
