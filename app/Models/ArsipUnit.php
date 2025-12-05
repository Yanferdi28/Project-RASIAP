<?php

namespace App\Models;

use App\Events\ArsipUnitCreated;
use App\Events\ArsipUnitStatusChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BerkasArsip;
use App\Models\KodeKlasifikasi;
use App\Models\UnitPengolah;
use App\Models\Kategori;
use App\Models\SubKategori;

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
    'berkas_arsip_id',
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
     * Scope to include commonly used relationships for better performance
     */
    public function scopeWithCommonRelationships($query)
    {
        return $query->with(['kodeKlasifikasi', 'kategori', 'subKategori', 'unitPengolah']);
    }

    /**
     * Scope to include verification relationship
     */
    public function scopeWithVerifier($query)
    {
        return $query->with(['verifier']);
    }

    /**
     * Boot the model and register event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($arsipUnit) {

            if ($arsipUnit->status === 'menunggu') {
                event(new ArsipUnitCreated($arsipUnit));
            }
        });

        // Make sure non-admin users can't change the unit_pengolah_arsip_id
        static::creating(function ($arsipUnit) {
            $user = auth()->user();
            if ($user && !$user->hasRole(['admin', 'superadmin']) && $user->unit_pengolah_id) {
                $arsipUnit->unit_pengolah_arsip_id = $user->unit_pengolah_id;
            }
        });

        static::updating(function ($arsipUnit) {
            $user = auth()->user();
            if ($user && !$user->hasRole(['admin', 'superadmin'])) {
                // Prevent non-admin users from changing the unit_pengolah_arsip_id
                $originalUnitPengolahId = $arsipUnit->getOriginal('unit_pengolah_arsip_id');
                $arsipUnit->unit_pengolah_arsip_id = $originalUnitPengolahId;
            }
        });
    }

    /**
     * Relasi ke BerkasArsip (Berkas)
     * <-- TAMBAHAN BARU
     */
    public function berkasArsip(): BelongsTo
    {
        return $this->belongsTo(BerkasArsip::class, 'berkas_arsip_id');
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
     * Method to approve the archive unit
     */
    public function approve($keterangan = null, $user = null)
    {
        $oldStatus = $this->status;

        $this->update([
            'status' => 'disetujui',
            'verifikasi_keterangan' => $keterangan,
            'verifikasi_oleh' => $user ? $user->id : Auth::id(),
            'verifikasi_tanggal' => now(),
        ]);

        if ($oldStatus !== $this->status) {
            event(new ArsipUnitStatusChanged($this, $oldStatus, $this->status));
        }
    }

    /**
     * Method to reject the archive unit
     */
    public function reject($keterangan = null, $user = null)
    {
        $oldStatus = $this->status;

        $this->update([
            'status' => 'ditolak',
            'verifikasi_keterangan' => $keterangan,
            'verifikasi_oleh' => $user ? $user->id : Auth::id(),
            'verifikasi_tanggal' => now(),
        ]);

        if ($oldStatus !== $this->status) {
            event(new ArsipUnitStatusChanged($this, $oldStatus, $this->status));
        }
    }

    /**
     * Scope: arsip menunggu verifikasi
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    /**
     * Scope: arsip yang sudah disetujui
     */
    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    /**
     * Scope: arsip yang ditolak
     */
    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }
}