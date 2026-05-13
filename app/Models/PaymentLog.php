<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $table = 'payment_logs';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'booking_id',
        'term_number',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'gateway_name',
        'gateway_response',
        'error_message',
        'qris_url',
    ];

    protected $casts = [
        'amount' => 'decimal:0',
        'gateway_response' => 'array',
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
     * Relationship ke PaymentTerm
     */
    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class, 'term_number', 'term_number')
            ->where('booking_id', $this->booking_id);
    }

    /**
     * Check apakah payment berhasil
     */
    public function isSuccess(): bool
    {
        return $this->payment_status === 'success';
    }

    /**
     * Check apakah payment gagal
     */
    public function isFailed(): bool
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Check apakah payment sedang diproses
     */
    public function isProcessing(): bool
    {
        return $this->payment_status === 'processing';
    }

    /**
     * Scope: filter payment yang berhasil
     */
    public function scopeSuccess($query)
    {
        return $query->where('payment_status', 'success');
    }

    /**
     * Scope: filter payment yang gagal
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /**
     * Scope: filter payment untuk booking tertentu
     */
    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    /**
     * Scope: filter payment untuk termin tertentu
     */
    public function scopeForTerm($query, $termNumber)
    {
        return $query->where('term_number', $termNumber);
    }

    /**
     * Mark payment sebagai success
     */
    public function markAsSuccess($transactionId = null)
    {
        $this->update([
            'payment_status' => 'success',
            'transaction_id' => $transactionId ?? $this->transaction_id,
        ]);

        return $this;
    }

    /**
     * Mark payment sebagai failed
     */
    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'payment_status' => 'failed',
            'error_message' => $errorMessage,
        ]);

        return $this;
    }
}
