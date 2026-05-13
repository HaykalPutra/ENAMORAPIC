<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    protected $table = 'booking_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'paket_id',
        'harga_saat_ini',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id', 'paket_id');
    }
}
