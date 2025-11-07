<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class SubKategori extends Model
{
    use HasFactory;

    protected $table = 'sub_kategori';

    protected $fillable = [
        'kategori_id',
        'nama_sub_kategori',
        'deskripsi',
    ];

    /**
     * Relasi ke model Kategori (Main Category).
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
    
    /**
     * Scope to get all records with caching
     */
    public function scopeAllCached($query)
    {
        return Cache::remember('sub_kategori_all', 3600, function () {
            return $query->get();
        });
    }
    
    /**
     * Get all records with caching, bypassing cache when needed
     */
    public static function getAllCached($bypassCache = false)
    {
        $cacheKey = 'sub_kategori_all';
        
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
            Cache::forget('sub_kategori_all');
        });
        
        static::updated(function () {
            Cache::forget('sub_kategori_all');
        });
        
        static::deleted(function () {
            Cache::forget('sub_kategori_all');
        });
    }
}