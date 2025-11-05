<?php

namespace App\Filament\Resources\ArsipUnits\Tables;

use App\Models\ArsipAktif;
use App\Models\ArsipUnit;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                Action::make('verifikasi')
                    ->label(function ($record) {
                        if ($record->status === 'pending') {
                            return 'Verifikasi';
                        } elseif ($record->status === 'diterima') {
                            return 'Ubah Verifikasi (Terima → Tolak)';
                        }
                        return 'Ubah Verifikasi (Tolak → Terima)';
                    })
                    ->icon('heroicon-o-check-badge')
                    ->color(function ($record) {
                        return match ($record->status) {
                            'pending'  => 'info',
                            'diterima' => 'success',
                            'ditolak'  => 'danger',
                            default    => 'info',
                        };
                    })
                    ->visible(function ($record) {
                        /** @var \App\Models\User $user */
                        $user = \Illuminate\Support\Facades\Auth::user();
                        return $user->hasAnyRole(['admin', 'operator']);
                    })
                    ->modalHeading('Verifikasi Arsip Unit')
                    ->modalDescription(function ($record) {
                        return $record->status === 'pending'
                            ? 'Pilih tindakan untuk verifikasi arsip unit ini.'
                            : 'Ubah verifikasi untuk arsip unit ini.';
                    })
                    ->modalWidth('md')
                    ->modalIcon('heroicon-o-check-badge')
                    ->form([
                        \Filament\Forms\Components\Radio::make('keputusan')
                            ->label('Keputusan')
                            ->options([
                                'diterima' => 'Terima',
                                'ditolak'  => 'Tolak',
                            ])
                            ->required()
                            ->default(fn ($record) => $record->status === 'pending' ? 'diterima' : $record->status),
                        \Filament\Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan (Opsional)')
                            ->placeholder('Tambahkan keterangan jika diperlukan'),
                    ])
                    ->action(function ($record, array $data) {
                        if ($data['keputusan'] === 'diterima') {
                            $record->accept($data['keterangan'] ?? null);
                        } else {
                            $record->reject($data['keterangan'] ?? null);
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Arsip Unit berhasil diperbarui')
                            ->success()
                            ->send();
                    }),

                Action::make('ubah_arsip_aktif')
                    ->label(fn ($record): string => $record->arsip_aktif_id ? 'Pemberkasan' : 'Pilih Berkas')
                    ->icon('heroicon-o-folder-open')
                    ->modalIcon('heroicon-o-folder-open')
                    ->color('warning')
                    ->modalHeading(fn ($record): string => $record->arsip_aktif_id ? 'Ubah Arsip Aktif untuk Unit Ini' : 'Pilih Arsip Aktif')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalWidth('md')
                    ->fillForm(fn ($record): array => [
                        'arsip_aktif_id' => $record->arsip_aktif_id,
                    ])
                    ->visible(function ($record) {
                        /** @var \App\Models\User $user */
                        $user = \Illuminate\Support\Facades\Auth::user();
                        if ($user->hasRole('operator')) {
                            return $record->status === 'pending';
                        }
                        return true;
                    })
                    ->form([
                        \Filament\Forms\Components\Select::make('arsip_aktif_id')
                            ->label('Pilih Arsip Aktif')
                            ->options(\App\Models\ArsipAktif::query()->pluck('nama_berkas', 'nomor_berkas'))
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

                ActionGroup::make([

                    Action::make('download_dokumen')
                        ->label('Download Dokumen')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('primary')
                        ->disabled(fn ($record) => empty($record) || empty($record->id_berkas))
                        ->url(function ($record) {
                            if (empty($record) || empty($record->id_berkas)) {
                                return '#';
                            }
                            return route('dokumen.show', ['id' => $record->id_berkas, 'action' => 'download']);
                        }, shouldOpenInNewTab: true)
                        ->visible(fn ($record) => $record && !empty($record->dokumen)),

                    Action::make('view_dokumen')
                        ->label('Preview Dokumen')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->disabled(fn ($record) => empty($record) || empty($record->id_berkas))
                        ->url(function ($record) {
                            if (empty($record) || empty($record->id_berkas)) {
                                return '#';
                            }
                            return route('dokumen.show', ['id' => $record->id_berkas, 'action' => 'view']);
                        }, shouldOpenInNewTab: true)
                        ->visible(fn ($record) => $record && !empty($record->dokumen)),
                ])
                    ->link()
                    ->label('Dokumen')
                    ->icon('heroicon-m-document-text')
                    ->color('gray')
                    ->tooltip('Aksi dokumen'),

                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->label('Kelola')
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->visible(function () {
                        /** @var \App\Models\User $user */
                        $user = \Illuminate\Support\Facades\Auth::user();
                        return ! $user->hasRole('operator');
                    })
                    ->link(),
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