<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeddingOrganizer extends Model
{
    use HasFactory;

    protected $table = 'wedding_organizer';
    protected $primaryKey = 'wo_id';

    protected $fillable = [
        'nama_wo',
        'pic_name',
        'no_wa',
        'email',
        'alamat',
        'instagram',
        'status',
        'catatan',
        'created_by',
    ];

    public const STATUSES = [
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
    ];
}
