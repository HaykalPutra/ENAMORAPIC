<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'booking_id',
        'user_id',
        'recipient_role',
        'phone_number',
        'notification_type',
        'subject',
        'message',
        'channel',
        'status',
        'metadata',
        'sent_at',
        'read_at',
        'failure_reason',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship ke Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    /**
     * Relationship ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Check apakah notifikasi sudah terkirim
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check apakah notifikasi sudah dibaca
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Check apakah notifikasi pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check apakah notifikasi gagal terkirim
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Mark notifikasi sebagai terkirim
     */
    public function markAsSent($sentAt = null)
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => $sentAt ?? now(),
        ]);

        return $this;
    }

    /**
     * Mark notifikasi sebagai gagal
     */
    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);

        return $this;
    }

    /**
     * Mark notifikasi sebagai dibaca
     */
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);

        return $this;
    }

    /**
     * Scope: filter notifikasi yang belum terkirim
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: filter notifikasi untuk role tertentu
     */
    public function scopeForRole($query, $role)
    {
        return $query->where('recipient_role', $role);
    }

    /**
     * Scope: filter notifikasi untuk tipe tertentu
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('notification_type', $type);
    }

    /**
     * Scope: filter notifikasi untuk booking tertentu
     */
    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    /**
     * Scope: filter notifikasi WhatsApp
     */
    public function scopeWhatsApp($query)
    {
        return $query->where('channel', 'whatsapp');
    }

    /**
     * Scope: filter notifikasi Dashboard
     */
    public function scopeDashboard($query)
    {
        return $query->where('channel', 'dashboard');
    }

    /**
     * Create notifikasi baru
     */
    public static function createNotification(array $data)
    {
        return self::create([
            'booking_id' => $data['booking_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'recipient_role' => $data['recipient_role'],
            'phone_number' => $data['phone_number'] ?? null,
            'notification_type' => $data['notification_type'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'channel' => $data['channel'] ?? 'dashboard',
            'metadata' => $data['metadata'] ?? null,
        ]);
    }
}
