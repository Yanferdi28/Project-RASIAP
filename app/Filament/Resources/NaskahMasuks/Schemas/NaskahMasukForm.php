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
                
                // Section 1: Informasi Dasar Naskah
                Section::make('Informasi Dasar Naskah')
                    ->columns(3) // Mengatur tata letak 3 kolom di dalam Section
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
                
                // Section 2: Identitas Pengirim
                Section::make('Identitas Pengirim')
                    ->columns(3) // Mengatur tata letak 3 kolom
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
                
                // Section 3: Detail Isi Naskah
                Section::make('Detail Isi Naskah')
                    ->columns(3) // Mengatur tata letak 3 kolom
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
                        // Kosongkan satu kolom agar textarea dimulai di baris baru
                        // Atau pastikan kolom berikutnya menggunakan columnSpanFull()
                        
                        Textarea::make('hal')
                            ->required()
                            ->maxLength(65535)
                            ->label('Perihal / Hal')
                            ->columnSpanFull(), // Mengambil lebar penuh (3 kolom)

                        Textarea::make('isi_ringkas')
                            ->maxLength(65535)
                            ->label('Isi Ringkas')
                            ->columnSpanFull(), // Mengambil lebar penuh (3 kolom)
                    ]),

                // Section 4: Dokumen Pendukung
                Section::make('Dokumen Pendukung')
                    ->columns(3) // Mengatur tata letak 3 kolom
                    ->schema([
                        FileUpload::make('file_naskah')
                            ->label('File Naskah Utama')
                            ->directory('naskah-masuk/utama') 
                            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->storeFiles()
                            ->columnSpan(1), // Mengambil 1 dari 3 kolom
                            
                        FileUpload::make('lampiran')
                            ->label('Lampiran Dokumen')
                            ->directory('naskah-masuk/lampiran')
                            ->multiple()
                            ->nullable()
                            ->columnSpan(2), // Mengambil 2 dari 3 kolom
                    ]),
            ]);
    }
}