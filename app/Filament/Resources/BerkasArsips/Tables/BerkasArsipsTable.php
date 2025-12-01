<?php

namespace App\Filament\Resources\BerkasArsips\Tables;

use App\Models\BerkasArsip;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ExportAction;
use App\Filament\Exports\BerkasArsipExporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class BerkasArsipsTable
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

                TextColumn::make('klasifikasi.kode_klasifikasi')
                    ->label('Kode Klasifikasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('unitPengolah.nama_unit')
                    ->label('Unit Pengolah')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_berkas')
                    ->label('Nama Berkas')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn ($state): ?string => strlen($state) > 50 ? $state : null),

                TextColumn::make('created_at')
                    ->label('Tanggal Buat Berkas')
                    ->dateTime('d/m/Y')
                    ->sortable(),

                TextColumn::make('retensi_aktif')
                    ->label('Retensi Aktif (Tahun)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('retensi_inaktif')
                    ->label('Retensi Inaktif (Tahun)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('penyusutan_akhir')
                    ->label('Penyusutan Akhir')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Permanen' => 'success',
                        'Musnah' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('lokasi_fisik')
                    ->label('Lokasi Fisik')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('uraian')
                    ->label('Uraian')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn ($state): ?string => strlen($state) > 50 ? $state : null),

                TextColumn::make('nomor_berkas')
                    ->label('Nomor Berkas')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('klasifikasi_id')
                    ->relationship('klasifikasi', 'kode_klasifikasi', fn (Builder $query) => $query->orderBy('kode_klasifikasi'))
                    ->searchable()
                    ->preload()
                    ->label('Kode Klasifikasi'),

                SelectFilter::make('unit_pengolah_id')
                    ->relationship('unitPengolah', 'nama_unit')
                    ->searchable()
                    ->preload()
                    ->label('Unit Pengolah'),
            ])
            ->modifyQueryUsing(fn ($query) => $query->withCommonRelationships())
            ->recordActions([
                ViewAction::make()
                    ->label('')
                    ->size('3md')
                    ->tooltip('Lihat Detail Berkas'),

                EditAction::make()
                    ->label('')
                    ->size('3md')
                    ->tooltip('Edit Berkas'),

                DeleteAction::make()
                    ->label('')
                    ->size('3md')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Berkas Arsip')
                    ->modalDescription('Apakah Anda yakin ingin menghapus berkas ini?')
                    ->tooltip('Hapus Berkas'),
            ])
            ->toolbarActions([
                Action::make('printCustomPdf')
                    ->label('Cetak Berkas')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->requiresConfirmation()
                    ->modalHeading('Cetak Berkas Arsip')
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
                    ])
                    ->action(function (array $data, \Filament\Tables\Contracts\HasTable $livewire) {
                        // Buat query dasar dari tabel yang sudah difilter
                        $query = $livewire->getFilteredTableQuery()->with(['klasifikasi']);

                        // Tambahkan filter berdasarkan tanggal jika disediakan
                        if (isset($data['tanggal_cetak_dari']) && $data['tanggal_cetak_dari']) {
                            $query->whereDate('created_at', '>=', $data['tanggal_cetak_dari']);
                        }

                        if (isset($data['tanggal_cetak_sampai']) && $data['tanggal_cetak_sampai']) {
                            $query->whereDate('created_at', '<=', $data['tanggal_cetak_sampai']);
                        }

                        $records = $query->get();

                        // Buat periode untuk ditampilkan di laporan
                        $dari = $data['tanggal_cetak_dari'] ?? now()->subMonth()->format('d/m/Y');
                        $sampai = $data['tanggal_cetak_sampai'] ?? now()->format('d/m/Y');
                        $periode = \Carbon\Carbon::parse($dari)->format('d F Y') . ' - ' . \Carbon\Carbon::parse($sampai)->format('d F Y');

                        // Ambil unit pengolah dari user yang login
                        $user = auth()->user();
                        $unitPengolah = $user->unitPengolah->nama_unit ?? 'Unit Pengolah';

                        $format = $data['format_ekspor'];

                        if ($format === 'pdf') {
                            $view = view('pdf.laporan-arsip-aktif', compact('records', 'unitPengolah', 'periode'))->render();

                            $pdf = Pdf::loadHtml($view)
                                    ->setPaper('a4', 'landscape');

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'Laporan Daftar Berkas Arsip.pdf'
                            );
                        } else { // Excel
                            // Use the new export class with proper styling
                            $export = new \App\Exports\BerkasArsipLaporanExport($records, $unitPengolah, $periode);
                            $filename = 'laporan_berkas_arsip_' . date('Y-m-d_H-i-s') . '.xlsx';

                            return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
                        }
                    }),

                \App\Actions\DaftarIsiBerkasAction::make(),


            ])
            ->defaultSort('created_at', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}