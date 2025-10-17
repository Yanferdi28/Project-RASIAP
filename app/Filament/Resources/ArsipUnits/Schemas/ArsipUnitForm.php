<?php

namespace App\Filament\Resources\ArsipUnits\Schemas;

use App\Models\KodeKlasifikasi;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ArsipUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
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
                        if (!$klasifikasi) {
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
                
                TextInput::make('indeks')
                    ->required(),

                Textarea::make('uraian_informasi')
                    ->label('Uraian Informasi')
                    ->required()
                    ->columnSpanFull(),
                
                DatePicker::make('tanggal')
                    ->required(),
                

                Grid::make(2)
                    ->schema([
                        TextInput::make('jumlah_nilai')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->columnSpan(1),

                        Select::make('jumlah_satuan')
                            ->label('Satuan')
                            ->options([
                                'Lembar' => 'Lembar',
                                'Jilid' => 'Jilid',
                                'Bundle' => 'Bundle',
                            ])
                            ->default('Lembar')
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                
                Select::make('tingkat_perkembangan')
                    ->options([
                        'Asli' => 'Asli',
                        'Salinan' => 'Salinan',
                        'Tembusan' => 'Tembusan',
                        'Pertinggal' => 'Pertinggal',
                    ])
                    ->required(),

                TextInput::make('skkaad')
                    ->disabled(),

                TextInput::make('retensi_aktif')
                    ->label('Retensi Aktif (Tahun)')
                    ->numeric()
                    ->disabled(),

                TextInput::make('retensi_inaktif')
                    ->label('Retensi Inaktif (Tahun)')
                    ->numeric()
                    ->disabled(),

                TextInput::make('ruangan'),

                TextInput::make('no_filling')
                    ->label('No. Filling Cabinet'),

                TextInput::make('no_laci')
                    ->label('No. Laci'),

                TextInput::make('no_folder')
                    ->label('No. Folder'),                   
            ]);
    }
}