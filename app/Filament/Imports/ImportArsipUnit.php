<?php

namespace App\Filament\Imports;

use App\Models\ArsipUnit;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Arr;

class ImportArsipUnit extends Importer
{
    protected static ?string $model = ArsipUnit::class;

    /**
     * Parse tanggal dari berbagai format (Excel serial, DD/MM/YYYY, YYYY-MM-DD)
     */
    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Jika berupa angka (Excel serial number)
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        // Jika format DD/MM/YYYY
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches)) {
            return $matches[3] . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        }

        // Jika format DD-MM-YYYY
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $value, $matches)) {
            return $matches[3] . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        }

        // Jika sudah format YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        // Coba parse dengan Carbon
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kode_klasifikasi')
                ->label('Kode Klasifikasi')
                ->requiredMapping()
                ->rules(['required', 'exists:kode_klasifikasi,kode_klasifikasi']),
            ImportColumn::make('indeks')
                ->label('Indeks')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('uraian_informasi')
                ->label('Uraian Informasi')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('tanggal')
                ->label('Tanggal')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('jumlah')
                ->label('Jumlah')
                ->rules(['nullable', 'numeric']),
            ImportColumn::make('satuan')
                ->label('Satuan')
                ->rules(['nullable', 'string']),
            ImportColumn::make('tingkat_perkembangan')
                ->label('Tingkat Perkembangan')
                ->rules(['nullable', 'string']),
            ImportColumn::make('unit_pengolah')
                ->label('Unit Pengolah')
                ->rules(['nullable', 'exists:unit_pengolah,nama_unit']),
            ImportColumn::make('retensi_aktif')
                ->label('Retensi Aktif')
                ->rules(['nullable', 'numeric']),
            ImportColumn::make('retensi_inaktif')
                ->label('Retensi Inaktif')
                ->rules(['nullable', 'numeric']),
            ImportColumn::make('skkaad')
                ->label('SKKAAD')
                ->rules(['nullable', 'string']),
            ImportColumn::make('ruangan')
                ->label('Ruangan')
                ->rules(['nullable', 'string']),
            ImportColumn::make('no_filling')
                ->label('No Filling/Rak/Lemari')
                ->rules(['nullable', 'string']),
            ImportColumn::make('no_laci')
                ->label('No Laci')
                ->rules(['nullable', 'string']),
            ImportColumn::make('no_folder')
                ->label('No Folder')
                ->rules(['nullable', 'string']),
            ImportColumn::make('no_box')
                ->label('No Box')
                ->rules(['nullable', 'string']),
            ImportColumn::make('keterangan')
                ->label('Keterangan')
                ->rules(['nullable', 'string']),
            ImportColumn::make('kategori')
                ->label('Kategori')
                ->rules(['nullable', 'exists:kategoris,nama_kategori']),
            ImportColumn::make('sub_kategori')
                ->label('Sub Kategori')
                ->rules(['nullable', 'exists:subkategoris,nama_sub_kategori']),
        ];
    }

    public function resolveRecord(): ?ArsipUnit
    {
        return new ArsipUnit();
    }

    public static function getModel(): string
    {
        return ArsipUnit::class;
    }

    public function beforeCreate(ArsipUnit $record, array $data): ArsipUnit
    {
        // Map kode_klasifikasi to kode_klasifikasi_id
        if (isset($data['kode_klasifikasi'])) {
            $kodeKlasifikasi = \App\Models\KodeKlasifikasi::where('kode_klasifikasi', $data['kode_klasifikasi'])->first();
            if ($kodeKlasifikasi) {
                $record->kode_klasifikasi_id = $kodeKlasifikasi->id;
            }
        }

        // Map unit_pengolah to unit_pengolah_id
        if (isset($data['unit_pengolah'])) {
            $unitPengolah = \App\Models\UnitPengolah::where('nama_unit', $data['unit_pengolah'])->first();
            if ($unitPengolah) {
                $record->unit_pengolah_id = $unitPengolah->id;
            }
        }

        // Map kategori to kategori_id
        if (isset($data['kategori'])) {
            $kategori = \App\Models\Kategori::where('nama_kategori', $data['kategori'])->first();
            if ($kategori) {
                $record->kategori_id = $kategori->id;
            }
        }

        // Map sub_kategori to sub_kategori_id
        if (isset($data['sub_kategori'])) {
            $subKategori = \App\Models\SubKategori::where('nama_sub_kategori', $data['sub_kategori'])->first();
            if ($subKategori) {
                $record->sub_kategori_id = $subKategori->id;
            }
        }

        // Set other values directly
        $record->indeks = $data['indeks'] ?? null;
        $record->uraian_informasi = $data['uraian_informasi'] ?? null;
        $record->tanggal = $this->parseDate($data['tanggal'] ?? null);
        $record->jumlah_nilai = $data['jumlah'] ?? null;
        $record->jumlah_satuan = $data['satuan'] ?? null;
        $record->tingkat_perkembangan = $data['tingkat_perkembangan'] ?? null;
        $record->retensi_aktif = $data['retensi_aktif'] ?? null;
        $record->retensi_inaktif = $data['retensi_inaktif'] ?? null;
        $record->skkaad = $data['skkaad'] ?? null;
        $record->ruangan = $data['ruangan'] ?? null;
        $record->no_filling = $data['no_filling'] ?? null;
        $record->no_laci = $data['no_laci'] ?? null;
        $record->no_folder = $data['no_folder'] ?? null;
        $record->no_box = $data['no_box'] ?? null;
        $record->keterangan = $data['keterangan'] ?? null;
        $record->status = 'menunggu';

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import selesai. ' . number_format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($import->failed_rows !== 0) {
            $body .= ' ' . number_format($import->failed_rows) . ' baris gagal diimpor.';
        }
        
        if ($import->pending_rows !== 0) {
            $body .= ' ' . number_format($import->pending_rows) . ' baris menunggu.';
        }

        return $body;
    }
}