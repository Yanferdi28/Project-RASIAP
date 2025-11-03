<?php

namespace App\Filament\Resources\ArsipUnits\Tables;

use App\Models\ArsipAktif;
use App\Models\ArsipUnit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArsipUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kodeKlasifikasi.kode_klasifikasi')
                    ->label('Kode Klasifikasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('indeks')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('jumlah_nilai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_satuan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tingkat_perkembangan')
                    ->searchable(),
                TextColumn::make('skkaad')
                    ->searchable(),
                TextColumn::make('retensi_aktif')
                    ->numeric(),
                TextColumn::make('retensi_inaktif')
                    ->numeric(),
                TextColumn::make('ruangan')
                    ->searchable(),
                TextColumn::make('no_filling')
                    ->searchable(),
                TextColumn::make('no_laci')
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
                Action::make('pilih_berkas')
                    ->label(fn (ArsipUnit $record): string => $record->arsip_aktif_id ? 'Ganti Berkas' : 'Pilih Berkas')
                    ->icon('heroicon-o-folder-open')
                    ->modalIcon('heroicon-o-folder-open')
                    ->color('warning')
                    ->modalHeading(fn (ArsipUnit $record): string => $record->arsip_aktif_id ? 'Ganti Berkas Arsip' : 'Pilih Berkas Arsip')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalWidth('md')
                    ->fillForm(fn (ArsipUnit $record): array => [
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
                    ->action(function (ArsipUnit $record, array $data): void {
                        $record->arsip_aktif_id = $data['arsip_aktif_id'];
                        $record->save();
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Naskah berhasil dimasukkan ke berkas!')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
