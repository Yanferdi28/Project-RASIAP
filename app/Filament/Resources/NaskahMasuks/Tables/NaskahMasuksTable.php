<?php

namespace App\Filament\Resources\NaskahMasuks\Tables;

use App\Models\ArsipAktif;
use App\Models\NaskahMasuk;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class NaskahMasuksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_naskah')
                    ->label('Nomor Naskah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_naskah')
                    ->label('Tgl. Naskah')
                    ->date()
                    ->sortable(),
                TextColumn::make('hal')
                    ->searchable()
                    ->label('Perihal')
                    ->limit(50)
                    ->tooltip(fn ($state): ?string => $state),
                TextColumn::make('arsipAktif.nama_berkas') 
                    ->label('Berkas Arsip')
                    ->placeholder('Belum Masuk Berkas')
                    ->badge()
                    ->sortable(),
                TextColumn::make('nama_pengirim')
                    ->label('Pengirim')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('pilih_berkas')
                    ->label(fn (NaskahMasuk $record): string => $record->arsip_aktif_id ? 'Ganti Berkas' : 'Pilih Berkas')
                    ->icon('heroicon-o-folder-open')
                    ->modalIcon('heroicon-o-folder-open')
                    ->color('warning')
                    ->modalHeading(fn (NaskahMasuk $record): string => $record->arsip_aktif_id ? 'Ganti Berkas Arsip' : 'Pilih Berkas Arsip')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalWidth('md')
                    ->fillForm(fn (NaskahMasuk $record): array => [
                        'arsip_aktif_id' => $record->arsip_aktif_id,
                    ])
                    ->schema([
                        Select::make('arsip_aktif_id')
                            ->label('Pilih Berkas Arsip Aktif')
                            ->options(ArsipAktif::all()->pluck('nama_berkas', 'nomor_berkas'))
                            ->required()
                            ->searchable()
                            ->helperText('Pilih berkas arsip aktif tempat dokumen ini akan disimpan.'),
                    ])
                    ->action(function (NaskahMasuk $record, array $data): void {
                        $record->arsip_aktif_id = $data['arsip_aktif_id'];
                        $record->save();
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Naskah berhasil dimasukkan ke berkas!')
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}