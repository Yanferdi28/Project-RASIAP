<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitPengolah extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'nama_unit',
    ];
}
