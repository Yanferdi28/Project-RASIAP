<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipUnit extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * (Opsional jika nama tabel Anda 'arsip_units')
     * @var string
     */
    protected $table = 'arsip_units'; 

    /**
     * Primary key kustom untuk model ini.
     *
     * @var string
     */
    protected $primaryKey = 'id_berkas';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_klasifikasi_id',
        'unit_pengolah_arsip_id',
        'retensi_aktif',
        'retensi_inaktif',
        'db_atau_dib',
        'indeks',
        'no_item_arsip',
        'uraian_informasi',
        'tanggal',
        'jumlah_nilai',
        'jumlah_satuan',
        'tingkat_perkembangan',
        'skkaad',
        'ruangan',
        'no_filling',
        'no_laci',
        'no_folder',
        'no_box',
        'dokumen',
        'keterangan',
    ];

    /**
     * Casts tipe data atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'jumlah_nilai' => 'integer',
        'retensi_aktif' => 'integer',
        'retensi_inaktif' => 'integer',
    ];

    public function kodeKlasifikasi(): BelongsTo
    {
        return $this->belongsTo(KodeKlasifikasi::class, 'kode_klasifikasi_id'); 
    }

    public function unitPengolah(): BelongsTo
    {
        return $this->belongsTo(UnitPengolah::class, 'unit_pengolah_arsip_id');
    }
}