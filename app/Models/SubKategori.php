<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}