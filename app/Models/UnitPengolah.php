<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitPengolah extends Model
{
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
}
