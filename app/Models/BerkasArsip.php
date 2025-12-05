<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BerkasArsip extends Model
{
    protected $table = 'berkas_arsip';
    
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
    ];
    
    protected $casts = [
        'retensi_aktif' => 'integer',
        'retensi_inaktif' => 'integer',
    ];

    /**
     * Scope to include commonly used relationships for better performance
     */
    public function scopeWithCommonRelationships($query)
    {
        return $query->with(['klasifikasi']);
    }
    
    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(KodeKlasifikasi::class, 'klasifikasi_id');
    }
    
    public function arsipUnits()
    {
        return $this->hasMany(\App\Models\ArsipUnit::class, 'berkas_arsip_id', 'nomor_berkas');
    }
    
    /**
     * Periksa apakah pengguna saat ini dapat melihat model ini
     */
    public function userCanView(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        return $user->can('view', $this);
    }
    
    /**
     * Periksa apakah pengguna saat ini dapat memperbarui model ini
     */
    public function userCanUpdate(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        
        return $user->can('update', $this);
    }
}