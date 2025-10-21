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
            // Kolom 'nomor_berkas' (primary key)
            ExportColumn::make('nomor_berkas')
                ->label('Nomor Berkas'),
            
            // Kolom 'nama_berkas'
            ExportColumn::make('nama_berkas')
                ->label('Nama Berkas'),
            
            // Kolom dari relasi 'klasifikasi'
            ExportColumn::make('klasifikasi.kode_klasifikasi')
                ->label('Kode Klasifikasi'),
            ExportColumn::make('klasifikasi.uraian')
                ->label('Uraian Klasifikasi'),

            // Kolom lainnya
            ExportColumn::make('retensi_aktif')
                ->label('Retensi Aktif'),
            ExportColumn::make('retensi_inaktif')
                ->label('Retensi Inaktif'),
            ExportColumn::make('penyusutan_akhir')
                ->label('Penyusutan Akhir'),
            ExportColumn::make('lokasi_fisik')
                ->label('Lokasi Fisik'),
            ExportColumn::make('kategori_berkas')
                ->label('Kategori Berkas'),
            ExportColumn::make('created_at')
                ->label('Tanggal Dibuat'),
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

    public function getCompletedNotification(Export $export): Notification
    {
        $failedRowsCount = $export->failed_rows;
        $body = 'Ekspor arsip aktif Anda telah selesai dan ' . number_format($export->successful_rows) . ' baris telah diekspor.';
        
        $notification = Notification::make()
            ->title('Ekspor Selesai')
            ->body($body)
            ->success();

        if ($failedRowsCount) {
            $notification
                ->title('Ekspor selesai sebagian')
                ->body($body . ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.')
                ->danger();
        }
        
        return $notification;
    }
}
