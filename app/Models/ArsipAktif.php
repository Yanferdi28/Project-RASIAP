<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArsipAktif extends Model
{
    protected $table = 'arsip_aktif';
    
    protected $primaryKey = 'nomor_berkas';
    
    public $incrementing = true;
    
    protected $fillable = [
        'nama_berkas',
        'klasifikasi_id',
        'retensi_aktif',
        'retensi_inaktif',
        'penyusutan_akhir',
        'lokasi_fisik',
        'uraian',
        'kategori_berkas',
        'kategori_id',
        'sub_kategori_id',
    ];
    
    protected $casts = [
        'retensi_aktif' => 'integer',
        'retensi_inaktif' => 'integer',
    ];
    
    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(KodeKlasifikasi::class, 'klasifikasi_id');
    }
    
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
    
    public function subKategori(): BelongsTo
    {
        return $this->belongsTo(SubKategori::class);
    }
    
    public function arsipUnits()
    {
        return $this->hasMany(\App\Models\ArsipUnit::class, 'arsip_aktif_id', 'nomor_berkas');
    }
}