<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freelance extends Model
{
    use HasFactory;

    protected $table = 'freelance';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'gear',
        'role',
        'harga',
        'domisili',
        'no_wa',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];
}
