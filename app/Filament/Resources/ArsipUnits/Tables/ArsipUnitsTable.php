<?php

namespace App\Filament\Resources\ArsipUnits\Tables;

<<<<<<< HEAD
use App\Models\ArsipAktif;
use App\Models\ArsipUnit;
=======
use Filament\Actions\Action;
>>>>>>> a61bd848b5887e85985ec0ab8338f533e1d4b9e6
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
<<<<<<< HEAD
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
=======
                Action::make('kelola_naskah')
                    ->label('Kelola Naskah')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->url(fn ($record) => $record->arsip_aktif_id 
                        ? route('filament.admin.resources.arsip-aktifs.edit', ['record' => $record->arsip_aktif_id]) . '/naskah-masuks'
                        : null
                    )
                    ->link()
                    ->visible(fn ($record) => $record->arsip_aktif_id !== null)
                    ->tooltip('Lihat dan kelola naskah yang terkait dengan arsip aktif ini'),
                Action::make('ubah_arsip_aktif')
                    ->label('Ubah Arsip Aktif')
                    ->icon('heroicon-o-folder-open')
                    ->modalIcon('heroicon-o-folder-open')
                    ->color('warning')
                    ->modalHeading(fn ($record): string => 
                        $record->arsip_aktif_id 
                            ? 'Ubah Arsip Aktif untuk Unit Ini' 
                            : 'Pilih Arsip Aktif'
                    )
                    ->modalSubmitActionLabel('Simpan')
                    ->modalWidth('md')
                    ->fillForm(fn ($record): array => [
                        'arsip_aktif_id' => $record->arsip_aktif_id,
                    ])
                    ->visible(fn ($record) => true) // Always visible to allow selecting when null
                    ->form([
                        \Filament\Forms\Components\Select::make('arsip_aktif_id')
                            ->label('Pilih Arsip Aktif')
                            ->options(\App\Models\ArsipAktif::all()->pluck('nama_berkas', 'nomor_berkas'))
                            ->required()
                            ->searchable()
                            ->helperText('Pilih arsip aktif tempat naskah akan dihubungkan.'),
                    ])
                    ->action(function ($record, array $data): void {
>>>>>>> a61bd848b5887e85985ec0ab8338f533e1d4b9e6
                        $record->arsip_aktif_id = $data['arsip_aktif_id'];
                        $record->save();
                        
                        \Filament\Notifications\Notification::make()
<<<<<<< HEAD
                            ->title('Naskah berhasil dimasukkan ke berkas!')
=======
                            ->title('Arsip Unit berhasil diperbarui!')
>>>>>>> a61bd848b5887e85985ec0ab8338f533e1d4b9e6
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
