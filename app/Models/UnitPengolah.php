<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class UnitPengolah extends Model
{
    protected $table = 'unit_pengolah';
    
    public $timestamps = false;
    protected $fillable = [
        'nama_unit',
    ];

    /**
     * Relasi ke users
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'unit_pengolah_id');
    }
    
    /**
     * Scope to get all records with caching
     */
    public function scopeAllCached($query)
    {
        return Cache::remember('unit_pengolah_all', 3600, function () {
            return $query->get();
        });
    }
    
    /**
     * Get all records with caching, bypassing cache when needed
     */
    public static function getAllCached($bypassCache = false)
    {
        $cacheKey = 'unit_pengolah_all';
        
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
            Cache::forget('unit_pengolah_all');
        });
        
        static::updated(function () {
            Cache::forget('unit_pengolah_all');
        });
        
        static::deleted(function () {
            Cache::forget('unit_pengolah_all');
        });
    }
}
