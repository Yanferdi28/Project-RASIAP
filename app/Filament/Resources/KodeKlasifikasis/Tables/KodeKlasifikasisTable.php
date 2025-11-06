<?php

namespace App\Filament\Resources\KodeKlasifikasis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KodeKlasifikasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_klasifikasi')
                    ->searchable(),
                TextColumn::make('uraian')
                    ->searchable(),
                TextColumn::make('retensi_aktif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('retensi_inaktif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status_akhir'),
                TextColumn::make('klasifikasi_keamanan'),
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
