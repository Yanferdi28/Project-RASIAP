<?php

namespace App\Filament\Exports;

use App\Models\ArsipAktif;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;
use Filament\Notifications\Notification;

class ArsipAktifExporter extends Exporter
{
    protected static ?string $model = ArsipAktif::class;

    public static bool $shouldQueue = false;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nomor_berkas')
                ->label('nomor_berkas'),
            
            ExportColumn::make('nama_berkas')
                ->label('nama_berkas'),
            
            ExportColumn::make('klasifikasi.kode_klasifikasi')
                ->label('klasifikasi')
                ->formatStateUsing(fn ($state, $record) => $record->klasifikasi?->kode_klasifikasi ?? ''),

            ExportColumn::make('retensi_aktif')
                ->label('retensi_aktif'),
            ExportColumn::make('retensi_inaktif')
                ->label('retensi_inaktif'),
            ExportColumn::make('penyusutan_akhir')
                ->label('penyusutan_akhir'),
            ExportColumn::make('lokasi_fisik')
                ->label('lokasi_fisik'),
            ExportColumn::make('uraian')
                ->label('uraian'),
            ExportColumn::make('keterangan')
                ->label('keterangan'),

            ExportColumn::make('kategori.nama_kategori')
                ->label('kategori_id')
                ->formatStateUsing(fn ($state, $record) => $record->kategori?->nama_kategori ?? ''),
            ExportColumn::make('subKategori.nama_sub_kategori')
                ->label('sub_kategori_id')
                ->formatStateUsing(fn ($state, $record) => $record->subKategori?->nama_sub_kategori ?? ''),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor arsip aktif Anda telah selesai dan ' . number_format($export->successful_rows) . ' baris telah diekspor.';

        if ($failedRowsCount = $export->failed_rows) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
