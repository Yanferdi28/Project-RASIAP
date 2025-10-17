<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipAktif extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'arsip_aktif';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'nomor_berkas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(KodeKlasifikasi::class, 'klasifikasi_id');
    }
}