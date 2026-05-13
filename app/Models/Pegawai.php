<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'gear',
        'role',
        'gaji',
        'domisili',
        'no_wa',
    ];

    protected $casts = [
        'gaji' => 'decimal:2',
    ];
}
