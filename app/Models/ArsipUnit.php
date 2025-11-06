<?php

namespace App\Models;

use App\Events\ArsipUnitCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ArsipAktif;
use App\Models\KodeKlasifikasi;
use App\Models\UnitPengolah;

class ArsipUnit extends Model
{
    use HasFactory;

     /**
      * Nama tabel yang terhubung dengan model ini.
      * @var string
      */
    protected $table = 'arsip_units'; 

    /**
      * Primary key kustom untuk model ini.
      * @var string
      */
    protected $primaryKey = 'id_berkas';

    /**
      * Atribut yang dapat diisi secara massal.
      * @var array<int, string>
      */
    protected $fillable = [
    'arsip_aktif_id',  
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
    'status',
    'verifikasi_keterangan',
    'verifikasi_oleh',
    'verifikasi_tanggal',
    'kategori_id',
    'sub_kategori_id',
];

     /**
      * Casts tipe data atribut.
      * @var array<string, string>
      */
    protected $casts = [
        'tanggal' => 'date',
        'jumlah_nilai' => 'integer',
        'retensi_aktif' => 'integer',
        'retensi_inaktif' => 'integer',
        'verifikasi_tanggal' => 'datetime',
     ];

    /**
     * Boot the model and register event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($arsipUnit) {

            if ($arsipUnit->status === 'pending') {
                event(new ArsipUnitCreated($arsipUnit));
            }
        });
    }

    /**
     * Relasi ke ArsipAktif (Berkas)
     * <-- TAMBAHAN BARU
     */
    public function arsipAktif(): BelongsTo
    {
        return $this->belongsTo(ArsipAktif::class, 'arsip_aktif_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function subKategori(): BelongsTo
    {
        return $this->belongsTo(SubKategori::class);
    }

    public function kodeKlasifikasi(): BelongsTo
    {
     return $this->belongsTo(KodeKlasifikasi::class, 'kode_klasifikasi_id'); 
    }



    public function unitPengolah(): BelongsTo
    {
        return $this->belongsTo(UnitPengolah::class, 'unit_pengolah_arsip_id');
    }
    
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikasi_oleh');
    }
    
    /**
     * Method to accept the archive unit
     */
    public function accept($keterangan = null, $user = null)
    {
        $this->update([
            'status' => 'diterima',
            'verifikasi_keterangan' => $keterangan,
            'verifikasi_oleh' => $user ? $user->id : Auth::id(),
            'verifikasi_tanggal' => now(),
        ]);
    }
    
    /**
     * Method to reject the archive unit
     */
    public function reject($keterangan = null, $user = null)
    {
        $this->update([
            'status' => 'ditolak',
            'verifikasi_keterangan' => $keterangan,
            'verifikasi_oleh' => $user ? $user->id : Auth::id(),
            'verifikasi_tanggal' => now(),
        ]);
    }
    
    /**
     * Scope to get pending archive units
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope to get accepted archive units
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'diterima');
    }
    
    /**
     * Scope to get rejected archive units
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'ditolak');
    }
}