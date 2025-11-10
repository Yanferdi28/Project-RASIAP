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
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\Builder;

class ArsipUnitsTable
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

                TextColumn::make('kodeKlasifikasi.kode_klasifikasi')
                    ->label('Kode Klasifikasi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('indeks')
                    ->searchable(),
                TextColumn::make('uraian_informasi')
                    ->label('Uraian Informasi')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn ($state): ?string => strlen($state) > 50 ? $state : null),
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('jumlah_nilai')
                    ->label('Jumlah')
                    ->formatStateUsing(function ($record) {
                        return $record->jumlah_nilai . ' ' . $record->jumlah_satuan;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tingkat_perkembangan')
                    ->searchable(),
                TextColumn::make('unitPengolah.nama_unit')
                    ->label('Unit Pengolah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('retensi_aktif')
                    ->numeric(),
                TextColumn::make('retensi_inaktif')
                    ->numeric(),
                TextColumn::make('skkaad')
                    ->searchable(),
                TextColumn::make('ruangan')
                    ->label('No Ruang')
                    ->searchable(),
                TextColumn::make('no_filling')
                    ->label('No Filling/Rak/Lemari')
                    ->searchable(),
                TextColumn::make('no_laci')
                    ->label('No Laci')
                    ->searchable(),
                TextColumn::make('no_folder')
                    ->label('No Folder')
                    ->searchable(),
                TextColumn::make('no_box')
                    ->label('No Box')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subKategori.nama_sub_kategori')
                    ->label('Sub Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(fn ($query) => $query->withCommonRelationships())
            ->defaultSort('id_berkas', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ])
                    ->label('Status Verifikasi'),
                    
                SelectFilter::make('kode_klasifikasi_id')
                    ->relationship('kodeKlasifikasi', 'kode_klasifikasi', fn (Builder $query) => $query->orderBy('kode_klasifikasi'))
                    ->searchable()
                    ->preload()
                    ->label('Kode Klasifikasi'),
                    
                Filter::make('tanggal_range')
                    ->form([
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_mulai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_selesai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['tanggal_mulai'] ?? null) {
                            $indicators['tanggal_mulai'] = 'Tanggal dari ' . $data['tanggal_mulai'];
                        }
                        if ($data['tanggal_selesai'] ?? null) {
                            $indicators['tanggal_selesai'] = 'Tanggal hingga ' . $data['tanggal_selesai'];
                        }
                        
                        return $indicators;
                    }),
                    
                SelectFilter::make('unit_pengolah_id')
                    ->relationship('unitPengolah', 'nama_unit', fn (Builder $query) => $query->orderBy('nama_unit'))
                    ->searchable()
                    ->preload()
                    ->label('Unit Pengolah'),
                    
                SelectFilter::make('kategori_id')
                    ->relationship('kategori', 'nama_kategori', fn (Builder $query) => $query->orderBy('nama_kategori'))
                    ->searchable()
                    ->preload()
                    ->label('Kategori'),
            ])
            ->recordActions([
                Action::make('verifikasi')
                    ->size('3md')
                    ->label('')
                    ->icon('heroicon-o-check-badge')
                    ->tooltip('Verifikasi')
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
                    ->size('3md')
                    ->label('')
                    ->icon('heroicon-o-folder-open')
                    ->tooltip(fn ($record): string => $record->arsip_aktif_id ? 'Ubah Berkas' : 'Pilih Berkas')
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
                        ->label('Download')
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
                        ->label('Preview')
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
                    ->dropdown()
                    ->size('3md')
                    ->label('')
                    ->icon('heroicon-m-document-text')
                    ->color('gray')
                    ->tooltip('Aksi dokumen'),

                ActionGroup::make([
                    ViewAction::make()
                        ->icon('heroicon-m-eye')
                        ->label('Lihat'),
                    EditAction::make()
                        ->icon('heroicon-m-pencil')
                        ->label('Edit'),
                ])
                    ->dropdown()
                    ->size('3md')
                    ->label('')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->visible(function () {
                        /** @var \App\Models\User $user */
                        $user = \Illuminate\Support\Facades\Auth::user();
                        return ! $user->hasRole('operator');
                    })
                    ->link(),
                        ])

            ->toolbarActions([
                
            ]);
    }
}