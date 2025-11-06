<?php

namespace App\Imports;

use App\Models\ArsipAktif;
use App\Models\KodeKlasifikasi;
use App\Models\Kategori;
use App\Models\SubKategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Throwable;

class ArsipAktifImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    public function model(array $row)
    {
        // Cari atau buat klasifikasi
        $klasifikasi = KodeKlasifikasi::firstOrCreate(
            ['kode_klasifikasi' => $row['kode_klasifikasi']],
            ['kode_klasifikasi' => $row['kode_klasifikasi'], 'uraian' => 'Imported from Excel']
        );
        
        // Cari kategori
        $kategori = null;
        if (isset($row['nama_kategori']) && !empty($row['nama_kategori'])) {
            $kategori = Kategori::where('nama_kategori', $row['nama_kategori'])->first();
        }
        
        // Cari sub kategori
        $subKategori = null;
        if (isset($row['nama_sub_kategori']) && !empty($row['nama_sub_kategori'])) {
            $subKategori = SubKategori::where('nama_sub_kategori', $row['nama_sub_kategori'])->first();
        }

        return new ArsipAktif([
            'nama_berkas' => $row['nama_berkas'] ?? '',
            'klasifikasi_id' => $klasifikasi->id,
            'retensi_aktif' => (int)($row['retensi_aktif'] ?? 0),
            'retensi_inaktif' => (int)($row['retensi_inaktif'] ?? 0),
            'penyusutan_akhir' => $row['penyusutan_akhir'] ?? '',
            'lokasi_fisik' => $row['lokasi_fisik'] ?? '',
            'uraian' => $row['uraian'] ?? '',
            'kategori_id' => $kategori ? $kategori->id : null,
            'sub_kategori_id' => $subKategori ? $subKategori->id : null,
            'created_at' => $row['created_at'] ?? now(),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_berkas' => 'required|string|max:255',
            'kode_klasifikasi' => 'required|string|max:255',
            'retensi_aktif' => 'integer|min:0',
            'retensi_inaktif' => 'integer|min:0',
        ];
    }
    
    public function customValidationMessages()
    {
        return [
            'nama_berkas.required' => 'Kolom nama_berkas wajib diisi.',
            'kode_klasifikasi.required' => 'Kolom kode_klasifikasi wajib diisi.',
        ];
    }
    
    public function onError(Throwable $e)
    {
        // Handle error saat import
        \Log::error('Error importing arsip aktif: ' . $e->getMessage());
    }
}