<?php

namespace App\Filament\Resources\NaskahMasuks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NaskahMasukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                
                Section::make('Informasi Dasar Naskah')
                    ->columns(3)
                    ->schema([
                        TextInput::make('nomor_naskah')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label('Nomor Naskah'),
                        DatePicker::make('tanggal_naskah')
                            ->required()
                            ->native(false)
                            ->label('Tanggal Naskah'),
                        DatePicker::make('tanggal_diterima')
                            ->required()
                            ->native(false)
                            ->label('Tanggal Diterima'),
                    ]),
                
                Section::make('Identitas Pengirim')
                    ->columns(3)
                    ->schema([
                        TextInput::make('nama_pengirim')
                            ->maxLength(255)
                            ->label('Nama Pengirim'),
                        TextInput::make('jabatan_pengirim')
                            ->maxLength(255)
                            ->label('Jabatan Pengirim'),
                        TextInput::make('instansi_pengirim')
                            ->maxLength(255)
                            ->label('Instansi Pengirim'),
                    ]),
                
                Section::make('Detail Isi Naskah')
                    ->columns(3)
                    ->schema([
                        Select::make('jenis_naskah')
                            ->options([
                                'Surat' => 'Surat',
                                'Memorandum' => 'Memorandum',
                                'Laporan' => 'Laporan',
                            ])
                            ->nullable(),
                        Select::make('sifat_naskah')
                            ->options([
                                'Biasa' => 'Biasa',
                                'Penting' => 'Penting',
                                'Rahasia' => 'Rahasia',
                            ])
                            ->nullable(),
                        
                        Textarea::make('hal')
                            ->required()
                            ->maxLength(65535)
                            ->label('Perihal / Hal')
                            ->columnSpanFull(),

                        Textarea::make('isi_ringkas')
                            ->maxLength(65535)
                            ->label('Isi Ringkas')
                            ->columnSpanFull(),
                    ]),

                // Section 4: Dokumen Pendukung
                Section::make('Dokumen Pendukung')
                    ->columns(3)
                    ->schema([
                        FileUpload::make('file_naskah')
                            ->label('File Naskah Utama')
                            ->directory('naskah-masuk/utama') 
                            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->storeFiles()
                            ->columnSpan(1),
                            
                        FileUpload::make('lampiran')
                            ->label('Lampiran Dokumen')
                            ->directory('naskah-masuk/lampiran')
                            ->multiple()
                            ->nullable()
                            ->columnSpan(2),
                    ]),
            ]);
    }
}