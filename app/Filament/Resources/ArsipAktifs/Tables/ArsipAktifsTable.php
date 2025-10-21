<?php

namespace App\Filament\Resources\ArsipAktifs\Tables;

use App\Models\ArsipAktif;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ExportAction;
use App\Filament\Exports\ArsipAktifExporter;
use Filament\Actions\Exports\Enums\ExportFormat;

class ArsipAktifsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_berkas')
                    ->searchable(),

                TextColumn::make('klasifikasi')
                    ->label('Klasifikasi')
                    ->getStateUsing(function (ArsipAktif $record) {
                        if ($record->klasifikasi) {
                            return "{$record->klasifikasi->kode_klasifikasi} - {$record->klasifikasi->uraian}";
                        }
                        return 'Tidak ada'; 
                    })
                    ->searchable(['klasifikasi.kode_klasifikasi', 'klasifikasi.uraian'])
                    ->sortable('klasifikasi.kode_klasifikasi'),

                TextColumn::make('retensi_aktif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('retensi_inaktif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('penyusutan_akhir')
                    ->searchable(),
                TextColumn::make('lokasi_fisik')
                    ->searchable(),
                TextColumn::make('kategori_berkas')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                ExportAction::make()
                    ->label('Ekspor ke Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(ArsipAktifExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ])
                    ->color('secondary'),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}