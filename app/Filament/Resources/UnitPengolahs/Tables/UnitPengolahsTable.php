<?php

namespace App\Filament\Resources\UnitPengolahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UnitPengolahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->getStateUsing(function ($rowLoop) {
                        return $rowLoop->iteration;
                    })
                    ->alignCenter(),

                TextColumn::make('nama_unit')
                    ->searchable(),
            ])
            ->filters([

            ])
            ->recordActions([
                EditAction::make()
                    ->label('')
                    ->size('3md')
                    ->tooltip('Edit Unit Pengolah'),
            ])
            ->toolbarActions([

            ]);
    }
}
