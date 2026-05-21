<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'lead';
    protected $primaryKey = 'lead_id';

    protected $fillable = [
        'nama',
        'no_wa',
        'sumber',
        'status',
        'catatan',
        'customer_id',
        'booking_id',
        'created_by',
        'last_contact_at',
        'next_follow_up_at',
    ];

    protected $casts = [
        'last_contact_at' => 'datetime',
        'next_follow_up_at' => 'date',
        'last_follow_up_sent_at' => 'datetime',
    ];

    public const STATUSES = [
        'prospect' => 'Prospek',
        'negotiation' => 'Negosiasi',
        'booked' => 'Booking',
        'completed' => 'Selesai',
        'lost' => 'Hilang',
    ];

    public function activities()
    {
        return $this->hasMany(LeadActivity::class, 'lead_id', 'lead_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}
