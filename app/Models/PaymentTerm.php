<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    protected $table = 'payment_terms';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'booking_id',
        'term_number',
        'term_percentage',
        'term_amount',
        'due_date',
        'payment_status',
        'paid_amount',
        'paid_at',
        'payment_method',
        'next_term_reminder_sent_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'term_percentage' => 'decimal:2',
        'term_amount' => 'decimal:0',
        'paid_amount' => 'decimal:0',
    ];

    /**
     * Relationship ke Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    /**
     * Check apakah termin sudah lunas
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check apakah termin overdue
     */
    public function isOverdue(): bool
    {
        return $this->payment_status === 'overdue' || 
               (now()->greaterThan($this->due_date) && $this->payment_status === 'unpaid');
    }

    /**
     * Check apakah termin belum dibayar
     */
    public function isUnpaid(): bool
    {
        return $this->payment_status === 'unpaid';
    }

    /**
     * Hitung sisa pembayaran yang harus dibayar
     */
    public function getRemainingAmount(): int
    {
        return $this->term_amount - $this->paid_amount;
    }

    /**
     * Scope: filter termin yang belum dibayar
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('payment_status', ['unpaid', 'overdue']);
    }

    /**
     * Scope: filter termin yang sudah dibayar
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope: filter termin yang overdue
     */
    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue')
            ->orWhere(function ($query) {
                $query->where('payment_status', 'unpaid')
                    ->whereDate('due_date', '<', now());
            });
    }

    /**
     * Update status pembayaran
     */
    public function markAsPaid($paidAmount, $paymentMethod = null)
    {
        $this->update([
            'paid_amount' => $paidAmount,
            'payment_status' => $paidAmount >= $this->term_amount ? 'paid' : 'unpaid',
            'paid_at' => now(),
            'payment_method' => $paymentMethod ?? $this->payment_method,
        ]);

        return $this;
    }

    /**
     * Mark sebagai overdue jika terlambat
     */
    public function markAsOverdueIfLate()
    {
        if ($this->isUnpaid() && now()->greaterThan($this->due_date)) {
            $this->update(['payment_status' => 'overdue']);
        }

        return $this;
    }
}
