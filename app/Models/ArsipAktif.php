<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];
    
    protected $casts = [
        'retensi_aktif' => 'integer',
        'retensi_inaktif' => 'integer',
    ];
    
    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(KodeKlasifikasi::class, 'klasifikasi_id');
    }
    
    public function naskahMasuks(): HasMany
    {
        return $this->hasMany(NaskahMasuk::class, 'arsip_aktif_id', 'nomor_berkas');
    }
}