<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananWebsite extends Model
{
    protected $table = 'pesanan_website';
    protected $primaryKey = 'pesanan_id';
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'booking_id',
        'paket_id',
        'nama_pemesan',
        'no_wa',
        'tanggal_booking',
        'lokasi',
        'status',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'created_at'      => 'datetime',
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id', 'paket_id');
    }

    public function booking()
    {
        return $this->belongsTo(\App\Models\Booking::class, 'booking_id', 'booking_id');
    }
}
