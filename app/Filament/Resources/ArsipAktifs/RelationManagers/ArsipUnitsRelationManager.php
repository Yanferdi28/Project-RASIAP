<?php

namespace App\Filament\Resources\ArsipAktifs\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;

class ArsipUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'arsipUnits';

    protected static ?string $title = 'Unit Arsip Terkait';

    protected static ?string $recordTitleAttribute = 'id_berkas';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id_berkas')
            ->columns([
                Tables\Columns\TextColumn::make('id_berkas')
                    ->label('ID Berkas')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('kodeKlasifikasi.kode_klasifikasi')
                    ->label('Kode Klasifikasi')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('kodeKlasifikasi.uraian')
                    ->label('Uraian Klasifikasi')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->kodeKlasifikasi->uraian ?? '')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('indeks')
                    ->label('Indeks')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('jumlah_nilai')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('jumlah_satuan')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->emptyStateHeading('Tidak ada unit arsip')
            ->emptyStateDescription('Belum ada unit arsip yang terkait dengan arsip aktif ini.')
            ->emptyStateIcon('heroicon-o-archive-box');
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->label('Lihat')
                ->color('info')
                ->url(fn ($record) => route('filament.admin.resources.arsip-units.edit', ['record' => $record->id_berkas]))
                ->icon('heroicon-o-eye'),
            Action::make('edit')
                ->label('Edit')
                ->color('warning')
                ->url(fn ($record) => route('filament.admin.resources.arsip-units.edit', ['record' => $record->id_berkas]))
                ->icon('heroicon-o-pencil'),
        ];
    }
}