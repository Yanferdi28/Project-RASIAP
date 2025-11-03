<?php

namespace App\Filament\Resources\ArsipAktifs\Tables;

use App\Models\ArsipAktif;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ExportAction;
use App\Filament\Exports\ArsipAktifExporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class ArsipAktifsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_berkas')
                    ->label('Nomor Berkas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nama_berkas')
                    ->label('Nama Berkas')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn ($state): ?string => strlen($state) > 50 ? $state : null),

                TextColumn::make('klasifikasi')
                    ->label('Klasifikasi')
                    ->getStateUsing(function (ArsipAktif $record) {
                        if ($record->klasifikasi) {
                            return "{$record->klasifikasi->kode_klasifikasi} - {$record->klasifikasi->uraian}";
                        }
                        return 'Tidak ada'; 
                    })
                    ->searchable(['klasifikasi.kode_klasifikasi', 'klasifikasi.uraian'])
                    ->sortable('klasifikasi.kode_klasifikasi')
                    ->limit(50)
                    ->tooltip(fn ($state): ?string => strlen($state) > 50 ? $state : null),



                TextColumn::make('kategori_berkas')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Asli' => 'success',
                        'Salinan' => 'warning',
                        'Tembusan' => 'info',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('retensi_aktif')
                    ->label('Retensi Aktif (Tahun)')
                    ->numeric()
                    ->sortable(),

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

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye'),

                EditAction::make(),
                
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Berkas Arsip')
                    ->modalDescription('Apakah Anda yakin ingin menghapus berkas ini?'),
            ])
            ->toolbarActions([
                ExportAction::make()
                    ->label('Ekspor ke Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(ArsipAktifExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ])
                    ->color('secondary'),

                Action::make('printCustomPdf')
                    ->label('Cetak Laporan PDF')
                    ->icon('heroicon-o-document-check')
                    ->color('danger')
                    ->action(function (\Filament\Tables\Contracts\HasTable $livewire) {
                        
                        $records = $livewire->getFilteredTableQuery()->get();
                        
                        $unitPengolah = 'RRI BANJARMASIN';
                        $periode = '01 JULI 2025-30 SEPTEMBER 2025';

                        $view = view('pdf.laporan-arsip-aktif', compact('records', 'unitPengolah', 'periode'))->render();

                        $pdf = Pdf::loadHtml($view)
                                ->setPaper('a4', 'landscape');

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'Laporan Daftar Berkas Arsip Aktif.pdf'
                        );
                    }),
                    
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('nomor_berkas', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}