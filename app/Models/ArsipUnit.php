<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipUnit extends Model
{
    use HasFactory;

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
        'retensi_aktif', // <-- Tambahan
        'retensi_inaktif', // <-- Tambahan
        'db_atau_dib',
        'indeks',
        'no_item_arsip',
        'uraian_informasi',
        'tanggal',
        'jumlah',
        'tingkat_perkembangan',
        'skkaad',
        'ruangan',
        'no_filling',
        'no_laci',
        'no_folder',
        'keterangan',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model KodeKlasifikasi.
     */
    public function kodeKlasifikasi(): BelongsTo
    {
        return $this->belongsTo(KodeKlasifikasi::class);
    }

    /**
     * Mendefinisikan relasi "belongsTo" ke model UnitPengolah.
     */
    public function unitPengolah(): BelongsTo
    {
        // Parameter kedua ('unit_pengolah_arsip_id') diperlukan karena nama foreign key tidak mengikuti konvensi Laravel (seharusnya unit_pengolah_id).
        return $this->belongsTo(UnitPengolah::class, 'unit_pengolah_arsip_id');
    }
}