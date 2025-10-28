<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NaskahMasuk extends Model
{
    protected $table = 'naskah_masuks';
    
    protected $fillable = [
        'nomor_naskah',
        'nama_pengirim',
        'jabatan_pengirim',
        'instansi_pengirim',
        'jenis_naskah',
        'sifat_naskah',
        'tanggal_naskah',
        'tanggal_diterima',
        'hal',
        'isi_ringkas',
        'file_naskah',
        'lampiran',
        'arsip_aktif_id',
    ];
    
    protected $casts = [
        'tanggal_naskah' => 'date',
        'tanggal_diterima' => 'date',
        'lampiran' => 'array',
    ];
    
    public function arsipAktif(): BelongsTo
    {
        return $this->belongsTo(ArsipAktif::class, 'arsip_aktif_id', 'nomor_berkas');
    }
}