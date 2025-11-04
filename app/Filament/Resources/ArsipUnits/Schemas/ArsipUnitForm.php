<?php

namespace App\Filament\Resources\ArsipUnits\Schemas;

use App\Models\KodeKlasifikasi;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class ArsipUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // 1) Klasifikasi & Unit
                Section::make('Klasifikasi & Unit')
                    ->columns(2)
                    ->schema([
                        Select::make('kode_klasifikasi_id')
                            ->label('Kode Klasifikasi')
                            ->relationship(name: 'kodeKlasifikasi')
                            ->getOptionLabelFromRecordUsing(fn (KodeKlasifikasi $record) => "{$record->kode_klasifikasi} - {$record->uraian}")
                            ->searchable(['kode_klasifikasi', 'uraian'])
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (?string $state, callable $set) {
                                if (blank($state)) {
                                    $set('retensi_aktif', null);
                                    $set('retensi_inaktif', null);
                                    $set('skkaad', null);
                                    return;
                                }

                                $klasifikasi = KodeKlasifikasi::find($state);
                                if (! $klasifikasi) {
                                    return;
                                }

                                $set('retensi_aktif', $klasifikasi->retensi_aktif);
                                $set('retensi_inaktif', $klasifikasi->retensi_inaktif);
                                $set('skkaad', $klasifikasi->klasifikasi_keamanan);
                            })
                            ->columnSpanFull(),

                        Select::make('unit_pengolah_arsip_id')
                            ->label('Unit Pengolah Arsip')
                            ->relationship(name: 'unitPengolah', titleAttribute: 'nama_unit')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                // 2) Deskripsi Arsip
                Section::make('Deskripsi Arsip')
                    ->columns(2)
                    ->schema([
                        TextInput::make('indeks')
                            ->label('Indeks')
                            ->required(),

                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->required(),

                        Textarea::make('uraian_informasi')
                            ->label('Uraian Informasi')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                // 3) Kuantitas
                Section::make('Kuantitas')
                    ->columns(2)
                    ->schema([
                        TextInput::make('jumlah_nilai')
                            ->label('Jumlah')
                            ->numeric()
                            ->required(),

                        Select::make('jumlah_satuan')
                            ->label('Satuan')
                            ->options([
                                'Lembar' => 'Lembar',
                                'Jilid' => 'Jilid',
                                'Bundle' => 'Bundle',
                            ])
                            ->default('Lembar')
                            ->required(),
                    ]),

                // 4) Retensi & Keamanan
                Section::make('Retensi & Keamanan')
                    ->columns(3)
                    ->schema([
                        Select::make('tingkat_perkembangan')
                            ->label('Tingkat Perkembangan')
                            ->options([
                                'Asli' => 'Asli',
                                'Salinan' => 'Salinan',
                                'Tembusan' => 'Tembusan',
                                'Pertinggal' => 'Pertinggal',
                            ])
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('skkaad')
                            ->label('Klasifikasi Keamanan (SKKAAD)')
                            ->disabled()
                            ->dehydrated(true)
                            ->columnSpan(1),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('retensi_aktif')
                                    ->label('Retensi Aktif (Tahun)')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(true),

                                TextInput::make('retensi_inaktif')
                                    ->label('Retensi Inaktif (Tahun)')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(true),
                            ])
                            ->columnSpan(1),
                    ]),

                // 5) Lokasi Fisik Arsip
                Section::make('Lokasi Fisik Arsip')
                    ->description('Detail lokasi penyimpanan fisik arsip.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('ruangan')->label('Ruangan'),
                        TextInput::make('no_filling')->label('No. Filling Cabinet'),
                        TextInput::make('no_laci')->label('No. Laci'),
                        TextInput::make('no_folder')->label('No. Folder'),
                        TextInput::make('no_box')->label('No. Box'),
                    ]),

                // 6) Dokumen Digital
                Section::make('Dokumen Digital')
                    ->description('Unggah hasil pindai (scan) dokumen.')
                    ->schema([
                        FileUpload::make('dokumen')
                            ->label('Upload Dokumen')
                            ->directory('arsip-dokumen')
                            ->preserveFilenames()
                            ->hiddenLabel()
                            ->visibility('public')
                            ->disk('public')
                            ->imagePreviewHeight('250')
                            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->maxSize(10240) // 10MB
                            ->downloadable()
                            ->openable(),
                    ]),
            ]);
    }
}
