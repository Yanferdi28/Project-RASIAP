<?php

namespace App\Filament\Resources\ArsipInaktifs\Tables;

use App\Models\ArsipInaktif;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArsipInaktifsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_berkas')
                    ->searchable(),

                TextColumn::make('klasifikasi')
                    ->label('Klasifikasi')
                    ->getStateUsing(function (ArsipInaktif $record) {
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
