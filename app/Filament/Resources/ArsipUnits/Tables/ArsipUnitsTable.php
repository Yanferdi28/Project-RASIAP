<?php

namespace App\Filament\Resources\ArsipUnits\Tables;

<<<<<<< HEAD
use App\Models\ArsipAktif;
use App\Models\ArsipUnit;
=======
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class ArsipUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_berkas')
                    ->label('ID Berkas')
                    ->searchable()
                    ->sortable(),
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
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('verifikasi_oleh.name')
                    ->label('Diverifikasi Oleh')
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('verifikasi_tanggal')
                    ->label('Tanggal Verifikasi')
                    ->dateTime()
                    ->sortable()
                    ->visibleFrom('md'),
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
                TextColumn::make('dokumen')
                    ->label('Dokumen')
                    ->formatStateUsing(function ($record) {
                        if ($record->dokumen) {
                            $fileName = basename($record->dokumen);
                            return $fileName;
                        }
                        return 'Tidak ada dokumen';
                    })
                    ->searchable()
                    ->visibleFrom('md'),
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
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ])
                    ->label('Status Verifikasi'),
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
                    ->label(fn ($record): string => 
                        $record->arsip_aktif_id 
                            ? 'Ganti Berkas' 
                            : 'Pilih Berkas'
                    )
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
                    ->visible(function ($record) {
                        // Only allow non-operators to see this action or operators when status is not verified
                        /** @var \App\Models\User $user */
                        $user = \Illuminate\Support\Facades\Auth::user();
                        if ($user->hasRole('operator')) {
                            return $record->status === 'pending';
                        }
                        return true; // Admin and user roles can always see this
                    })
                    ->form([
                        \Filament\Forms\Components\Select::make('arsip_aktif_id')
                            ->label('Pilih Arsip Aktif')
                            ->options(\App\Models\ArsipAktif::all()->pluck('nama_berkas', 'nomor_berkas'))
                            ->required()
                            ->searchable()
                            ->helperText('Pilih arsip aktif tempat naskah akan dihubungkan.'),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->arsip_aktif_id = $data['arsip_aktif_id'];
                        $record->save();
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Arsip Unit berhasil diperbarui!')
                            ->success()
                            ->send();
                    }),
                EditAction::make()
                    ->visible(function () {
                        /** @var \App\Models\User $user */
                        $user = \Illuminate\Support\Facades\Auth::user();
                        return !$user->hasRole('operator');
                    }),
                DeleteAction::make()
                    ->visible(function () {
                        /** @var \App\Models\User $user */
                        $user = \Illuminate\Support\Facades\Auth::user();
                        return !$user->hasRole('operator');
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(function () {
                            /** @var \App\Models\User $user */
                            $user = \Illuminate\Support\Facades\Auth::user();
                            return !$user->hasRole('operator');
                        }),
                ])->visible(function () {
                    /** @var \App\Models\User $user */
                    $user = \Illuminate\Support\Facades\Auth::user();
                    return !$user->hasRole('operator');
                }),
            ]);
    }
}