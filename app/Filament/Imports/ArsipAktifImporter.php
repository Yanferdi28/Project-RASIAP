<?php

namespace App\Filament\Imports;

use App\Models\ArsipAktif;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ArsipAktifImporter extends Importer
{
    protected static ?string $model = ArsipAktif::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama_berkas')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('klasifikasi')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('retensi_aktif')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('retensi_inaktif')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('penyusutan_akhir')
                ->rules(['max:255']),
            ImportColumn::make('lokasi_fisik')
                ->rules(['max:255']),
            ImportColumn::make('uraian'),
            ImportColumn::make('kategori_id')
                ->requiredMapping()
                ->relationship(
                    resolveUsing: fn ($state) => \App\Models\Kategori::where('nama_kategori', $state)->first(),
                )
                ->rules(['required']),
            ImportColumn::make('sub_kategori_id')
                ->requiredMapping()
                ->relationship(
                    resolveUsing: fn ($state) => \App\Models\SubKategori::where('nama_sub_kategori', $state)->first(),
                )
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ArsipAktif
    {
        // Check if a record already exists with the same nama_berkas
        $record = ArsipAktif::firstOrNew([
            'nama_berkas' => $this->data['nama_berkas'],
        ]);

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your arsip aktif import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
