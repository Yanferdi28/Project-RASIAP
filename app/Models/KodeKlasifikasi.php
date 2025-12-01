<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class KodeKlasifikasi extends Model
{
    use HasFactory;

    protected $table = 'kode_klasifikasi';

    protected $fillable = [
        'kode_klasifikasi',
        'kode_klasifikasi_induk',
        'uraian',
        'retensi_aktif',
        'retensi_inaktif',
        'status_akhir',
        'klasifikasi_keamanan',
    ];
    
    /**
     * Scope to get all records with caching
     */
    public function scopeAllCached($query)
    {
        return Cache::remember('kode_klasifikasi_all', 3600, function () {
            return $query->get();
        });
    }
    
    /**
     * Get all records with caching, bypassing cache when needed
     */
    public static function getAllCached($bypassCache = false)
    {
        $cacheKey = 'kode_klasifikasi_all';
        
        if ($bypassCache) {
            Cache::forget($cacheKey);
        }
        
        return Cache::remember($cacheKey, 3600, function () {
            return static::all();
        });
    }
    
    /**
     * Clear the cached records when a record is created, updated, or deleted
     */
    protected static function booted()
    {
        static::created(function () {
            Cache::forget('kode_klasifikasi_all');
        });
        
        static::updated(function () {
            Cache::forget('kode_klasifikasi_all');
        });
        
        static::deleted(function () {
            Cache::forget('kode_klasifikasi_all');
        });
    }
}
