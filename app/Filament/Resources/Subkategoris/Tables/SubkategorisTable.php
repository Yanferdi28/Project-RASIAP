<?php

namespace App\Filament\Resources\Subkategoris\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class SubkategorisTable
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

                TextColumn::make('kategori.nama_kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Setiap Saat' => 'success',
                        'Berkala' => 'info',
                        'Serta Merta' => 'warning',
                        'Dikecualikan' => 'danger',
                        default => 'secondary',
                    })
                    ->searchable()
                    ->sortable()
                    ->label('Kategori Utama'),


                TextColumn::make('nama_sub_kategori')
                    ->searchable()
                    ->sortable()
                    ->label('Item Informasi (Sub Kategori)'),
                    
                TextColumn::make('deskripsi')
                    ->limit(50)
                    ->tooltip(fn ($state) => $state)
                    ->label('Keterangan'),
            ])
            ->filters([

            ])
            ->recordActions([
                EditAction::make()
                    ->label('')
                    ->size('3md'),
                DeleteAction::make()
                    ->label('')
                    ->size('3md'),
            ])
            ->toolbarActions([

            ]);
    }
}
