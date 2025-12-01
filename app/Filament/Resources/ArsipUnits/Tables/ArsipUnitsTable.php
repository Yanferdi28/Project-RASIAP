<?php

namespace App\Filament\Resources\ArsipUnits\Tables;

use App\Models\BerkasArsip;
use App\Models\ArsipUnit;
use App\Models\UnitPengolah;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
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
use Barryvdh\DomPDF\Facade\Pdf;

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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tingkat_perkembangan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('unitPengolah.nama_unit')
                    ->label('Unit Pengolah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('retensi_aktif')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('retensi_inaktif')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('skkaad')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ruangan')
                    ->label('No Ruang')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('no_filling')
                    ->label('No Filling/Rak/Lemari')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('no_laci')
                    ->label('No Laci')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('no_folder')
                    ->label('No Folder')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('no_box')
                    ->label('No Box')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->modifyQueryUsing(function ($query) {
                $user = \Illuminate\Support\Facades\Auth::user();

                // Apply common relationships
                $query = $query->withCommonRelationships();

                // Restrict records to user's unit if user is not admin, superadmin, operator or manajemen
                if (!$user->hasRole(['admin', 'superadmin', 'operator', 'manajemen']) && $user->unit_pengolah_id) {
                    $query->where('unit_pengolah_arsip_id', $user->unit_pengolah_id);
                }

                // Operator can only see records with assigned category (not null or "-")
                if ($user->hasRole('operator')) {
                    $query->whereNotNull('kategori_id')
                          ->where('kategori_id', '!=', '')
                          ->whereHas('kategori', function($q) {
                              $q->where('nama_kategori', '!=', '-')
                                ->where('nama_kategori', '!=', '');
                          });
                }

                return $query;
            })
            ->defaultSort('id_berkas', 'asc')
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

                Action::make('ubah_berkas_arsip')
                    ->size('3md')
                    ->label('')
                    ->icon('heroicon-o-folder-open')
                    ->tooltip(fn ($record): string => $record->berkas_arsip_id ? 'Ubah Berkas' : 'Pilih Berkas')
                    ->modalIcon('heroicon-o-folder-open')
                    ->color('warning')
                    ->modalHeading(fn ($record): string => $record->berkas_arsip_id ? 'Ubah Berkas Arsip untuk Unit Ini' : 'Pilih Berkas Arsip')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalWidth('md')
                    ->fillForm(fn ($record): array => [
                        'berkas_arsip_id' => $record->berkas_arsip_id,
                    ])
                    ->visible(function ($record) {
                        /** @var \App\Models\User $user */
                        $user = \Illuminate\Support\Facades\Auth::user();
                        if ($user->hasRole('manajemen')) {
                            return false; // Management role cannot select/change berkas arsip
                        }
                        if ($user->hasRole('operator')) {
                            return $record->status === 'pending';
                        }
                        return true;
                    })
                    ->form([
                        \Filament\Forms\Components\Select::make('berkas_arsip_id')
                            ->label('Pilih Berkas Arsip')
                            ->options(\App\Models\BerkasArsip::query()->pluck('nama_berkas', 'nomor_berkas'))
                            ->required()
                            ->searchable()
                            ->helperText('Pilih berkas arsip tempat naskah akan dihubungkan.'),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->berkas_arsip_id = $data['berkas_arsip_id'];
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
                        ->label('Lihat')
                        ->visible(function ($record) {
                            /** @var \App\Models\User $user */
                            $user = \Illuminate\Support\Facades\Auth::user();
                            return $user->can('view', $record);
                        }),
                    EditAction::make()
                        ->icon('heroicon-m-pencil')
                        ->label('Edit')
                        ->visible(function ($record) {
                            /** @var \App\Models\User $user */
                            $user = \Illuminate\Support\Facades\Auth::user();
                            return $user->can('update', $record);
                        }),
                ])
                    ->dropdown()
                    ->size('3md')
                    ->label('')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->visible(function ($record) {
                        /** @var \App\Models\User $user */
                        $user = \Illuminate\Support\Facades\Auth::user();
                        // Show dropdown if user has permission to view OR update (or both)
                        return $user->can('view', $record) || $user->can('update', $record);
                    })
                    ->link(),
                        ])

            ->toolbarActions([
                Action::make('printArsipUnit')
                    ->label('Cetak Arsip Unit')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->requiresConfirmation()
                    ->modalHeading('Cetak Arsip Unit')
                    ->modalDescription('Pilih format ekspor dan rentang tanggal')
                    ->form([
                        \Filament\Forms\Components\Select::make('format_ekspor')
                            ->label('Format Ekspor')
                            ->options([
                                'pdf' => 'PDF',
                                'excel' => 'Excel',
                            ])
                            ->default('pdf')
                            ->required(),

                        \Filament\Forms\Components\DatePicker::make('tanggal_cetak_dari')
                            ->label('Dari Tanggal')
                            ->displayFormat('d/m/Y')
                            ->extraInputAttributes(['placeholder' => 'Pilih tanggal mulai'])
                            ->required(),

                        \Filament\Forms\Components\DatePicker::make('tanggal_cetak_sampai')
                            ->label('Sampai Tanggal')
                            ->displayFormat('d/m/Y')
                            ->extraInputAttributes(['placeholder' => 'Pilih tanggal akhir'])
                            ->required(),

                        \Filament\Forms\Components\Select::make('status_filter')
                            ->label('Filter Status')
                            ->options([
                                '' => 'Semua Status',
                                'pending' => 'Pending',
                                'diterima' => 'Diterima',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default(''),

                        \Filament\Forms\Components\Select::make('unit_pengolah_filter')
                            ->label('Filter Unit Pengolah')
                            ->options(\App\Models\UnitPengolah::all()->pluck('nama_unit', 'id'))
                            ->multiple()
                            ->placeholder('Pilih Unit Pengolah')
                            ->visible(fn () => \Illuminate\Support\Facades\Auth::user()->hasRole(['admin', 'superadmin', 'operator'])),
                    ])
                    ->action(function (array $data, \Filament\Tables\Contracts\HasTable $livewire) {
                        // Dapatkan user yang sedang login
                        $user = \Illuminate\Support\Facades\Auth::user();

                        // Buat query dasar dari tabel yang sudah difilter
                        $query = $livewire->getFilteredTableQuery()->with(['kodeKlasifikasi', 'unitPengolah']);

                        // Tambahkan filter berdasarkan tanggal jika disediakan
                        if (isset($data['tanggal_cetak_dari']) && $data['tanggal_cetak_dari']) {
                            $query->whereDate('tanggal', '>=', $data['tanggal_cetak_dari']);
                        }

                        if (isset($data['tanggal_cetak_sampai']) && $data['tanggal_cetak_sampai']) {
                            $query->whereDate('tanggal', '<=', $data['tanggal_cetak_sampai']);
                        }

                        // Tambahkan filter berdasarkan status jika disediakan
                        if (isset($data['status_filter']) && !empty($data['status_filter'])) {
                            $query->where('status', $data['status_filter']);
                        }

                        // Tambahkan filter berdasarkan unit pengolah jika disediakan
                        // TAPI hanya berlaku untuk admin, superadmin, atau operator
                        if (isset($data['unit_pengolah_filter']) && !empty($data['unit_pengolah_filter'])) {
                            if ($user->hasRole(['admin', 'superadmin', 'operator'])) {
                                // Handle multiple unit pengolah selection
                                $query->whereIn('unit_pengolah_arsip_id', $data['unit_pengolah_filter']);
                            }
                            // Jika bukan admin/operator, filter ini diabaikan
                        }
                        // Perhatian: Query yang diambil dengan getFilteredTableQuery()
                        // sudah melewati modifyQueryUsing() yang sudah menerapkan
                        // pembatasan akses berdasarkan role user

                        $records = $query->get();

                        // Buat periode untuk ditampilkan di laporan
                        $dari = $data['tanggal_cetak_dari'] ?? now()->subMonth()->format('d/m/Y');
                        $sampai = $data['tanggal_cetak_sampai'] ?? now()->format('d/m/Y');
                        $periode = \Carbon\Carbon::parse($dari)->format('d F Y') . ' - ' . \Carbon\Carbon::parse($sampai)->format('d F Y');

                        // Tentukan unit pengolah untuk ditampilkan di laporan
                        // Jika filter unit pengolah digunakan, tampilkan semua unit yang dipilih
                        if (isset($data['unit_pengolah_filter']) && !empty($data['unit_pengolah_filter'])) {
                            $selectedUnits = \App\Models\UnitPengolah::whereIn('id', $data['unit_pengolah_filter'])->pluck('nama_unit')->toArray();
                            $unitPengolah = implode(', ', $selectedUnits);
                        } else {
                            // Ambil unit pengolah dari user yang login
                            $user = auth()->user();
                            $unitPengolah = $user->unitPengolah->nama_unit ?? 'Unit Pengolah';
                        }

                        $format = $data['format_ekspor'];

                        if ($format === 'pdf') {
                            $view = view('pdf.laporan-arsip-unit', compact('records', 'unitPengolah', 'periode'))->render();

                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHtml($view)
                                    ->setPaper('a4', 'landscape');

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'Laporan Daftar Arsip Unit.pdf'
                            );
                        } else { // Excel
                            // Use the new export class with proper styling
                            $export = new \App\Exports\ArsipUnitLaporanExport($records, $unitPengolah, $periode);
                            $filename = 'laporan_arsip_unit_' . date('Y-m-d_H-i-s') . '.xlsx';

                            return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
                        }
                    }),
            ]);
    }
}