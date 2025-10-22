<?php

namespace App\Filament\Resources\ArsipAktifs\Tables;

use App\Models\ArsipAktif;
use pxlrbt\FilamentExcel\Actions\ExportAction as PdfExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ExportAction;
use App\Filament\Exports\ArsipAktifExporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Columns\Column;

class ArsipAktifsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_berkas')
                    ->searchable(),

                TextColumn::make('klasifikasi')
                    ->label('Klasifikasi')
                    ->getStateUsing(function (ArsipAktif $record) {
                        if ($record->klasifikasi) {
                            return "{$record->klasifikasi->kode_klasifikasi} - {$record->klasifikasi->uraian}";
                        }
                        return 'Tidak ada'; 
                    })
                    ->searchable(['klasifikasi.kode_klasifikasi', 'klasifikasi.uraian'])
                    ->sortable('klasifikasi.kode_klasifikasi'),

                TextColumn::make('retensi_aktif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('retensi_inaktif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('penyusutan_akhir')
                    ->searchable(),
                TextColumn::make('lokasi_fisik')
                    ->searchable(),
                TextColumn::make('kategori_berkas')
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
                EditAction::make(),
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

                PdfExportAction::make('print_pdf')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->exports([
                        ExcelExport::make('pdf') // Beri nama 'pdf'
                            ->withFilename('Laporan Arsip Aktif.pdf')
                            // INI PENGGANTI ->asPdf() atau ->withPdf()
                            ->withWriterType(Excel::DOMPDF) 
                            // INI UNTUK MENGATASI ERROR "not mounted"
                            ->withColumns([
                                Column::make('nomor_berkas')->heading('Nomor Berkas'),
                                Column::make('nama_berkas')->heading('Nama Berkas'),
                                Column::make('klasifikasi.kode_klasifikasi')->heading('Kode Klasifikasi'),
                                Column::make('klasifikasi.uraian')->heading('Uraian Klasifikasi'),
                                Column::make('retensi_aktif')->heading('Retensi Aktif'),
                                Column::make('retensi_inaktif')->heading('Retensi Inaktif'),
                                Column::make('penyusutan_akhir')->heading('Penyusutan Akhir'),
                                Column::make('lokasi_fisik')->heading('Lokasi Fisik'),
                                Column::make('kategori_berkas')->heading('Kategori Berkas'),
                                Column::make('created_at')->heading('Tanggal Dibuat'),
                            ]),
                    ]),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}