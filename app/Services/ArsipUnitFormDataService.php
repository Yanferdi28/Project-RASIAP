<?php

namespace App\Services;

use App\Models\KodeKlasifikasi;
use App\Models\UnitPengolah;
use App\Models\Kategori;
use Illuminate\Support\Facades\Cache;

class ArsipUnitFormDataService
{
    /**
     * Get all required data for the arsip unit form with caching
     */
    public static function getFormData(): array
    {
        return [
            'kode_klasifikasi_options' => Cache::remember('form.kode_klasifikasi_options', 3600, function () {
                return KodeKlasifikasi::orderBy('kode_klasifikasi')
                    ->get(['id', 'kode_klasifikasi', 'uraian', 'retensi_aktif', 'retensi_inaktif', 'klasifikasi_keamanan'])
                    ->mapWithKeys(function ($item) {
                        return [$item->id => $item->kode_klasifikasi . ' - ' . $item->uraian];
                    })
                    ->toArray();
            }),
            'unit_pengolah_options' => Cache::remember('form.unit_pengolah_options', 3600, function () {
                return UnitPengolah::orderBy('nama_unit')
                    ->get(['id', 'nama_unit'])
                    ->mapWithKeys(function ($item) {
                        return [$item->id => $item->nama_unit];
                    })
                    ->toArray();
            }),
            'kategori_options' => Cache::remember('form.kategori_options', 3600, function () {
                return Kategori::orderBy('nama_kategori')
                    ->get(['id', 'nama_kategori'])
                    ->mapWithKeys(function ($item) {
                        return [$item->id => $item->nama_kategori];
                    })
                    ->toArray();
            }),
        ];
    }
    
    /**
     * Get sub-kategori options for a specific category
     */
    public static function getSubKategoriOptions($kategoriId): array
    {
        if (!$kategoriId) {
            return [];
        }
        
        $cacheKey = "form.sub_kategori_options.{$kategoriId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($kategoriId) {
            return \App\Models\SubKategori::where('kategori_id', $kategoriId)
                ->orderBy('nama_sub_kategori')
                ->get(['id', 'nama_sub_kategori'])
                ->mapWithKeys(function ($item) {
                    return [$item->id => $item->nama_sub_kategori];
                })
                ->toArray();
        });
    }
}