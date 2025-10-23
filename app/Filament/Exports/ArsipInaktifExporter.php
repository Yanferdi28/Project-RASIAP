<?php

namespace App\Filament\Exports;

use App\Models\ArsipInaktif;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ArsipInaktifExporter extends Exporter
{
    protected static ?string $model = ArsipInaktif::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nomor_berkas'),
            ExportColumn::make('nama_berkas'),
            ExportColumn::make('klasifikasi.id'),
            ExportColumn::make('retensi_aktif'),
            ExportColumn::make('retensi_inaktif'),
            ExportColumn::make('penyusutan_akhir'),
            ExportColumn::make('lokasi_fisik'),
            ExportColumn::make('uraian'),
            ExportColumn::make('kategori_berkas'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your arsip inaktif export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
