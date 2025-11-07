<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Kategori extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'kategori';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function subKategori(): HasMany 
    {
        return $this->hasMany(SubKategori::class);
    }
    
    /**
     * Scope to get all records with caching
     */
    public function scopeAllCached($query)
    {
        return Cache::remember('kategori_all', 3600, function () {
            return $query->get();
        });
    }
    
    /**
     * Get all records with caching, bypassing cache when needed
     */
    public static function getAllCached($bypassCache = false)
    {
        $cacheKey = 'kategori_all';
        
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
            Cache::forget('kategori_all');
        });
        
        static::updated(function () {
            Cache::forget('kategori_all');
        });
        
        static::deleted(function () {
            Cache::forget('kategori_all');
        });
    }
}