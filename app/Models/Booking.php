<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $primaryKey = 'booking_id';
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'nama_client',
        'tgl_booking',
        'tgl_acara',
        'tgl_prewedd',
        'lokasi_prewedd',
        'lokasi_wedding',
        'total_transaksi',
        'status_pembayaran',
        'job_status',
        'total_termin',
        'booking_status',
    ];

    protected $casts = [
        'tgl_booking' => 'date',
        'tgl_acara'   => 'date',
        'tgl_prewedd' => 'date',
        'total_transaksi' => 'decimal:0',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function details()
    {
        return $this->hasMany(BookingDetail::class, 'booking_id', 'booking_id');
    }

    public function paket()
    {
        return $this->hasManyThrough(
            Paket::class,
            BookingDetail::class,
            'booking_id',
            'paket_id',
            'booking_id',
            'paket_id'
        );
    }

    /**
     * Relationship ke PaymentTerms
     */
    public function paymentTerms()
    {
        return $this->hasMany(PaymentTerm::class, 'booking_id', 'booking_id');
    }

    /**
     * Relationship ke Notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'booking_id', 'booking_id');
    }

    /**
     * Relationship ke PaymentLogs
     */
    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class, 'booking_id', 'booking_id');
    }

    public function pesananWebsite()
    {
        return $this->hasOne(PesananWebsite::class, 'booking_id', 'booking_id');
    }

    public function getDisplayPaketNameAttribute(): string
    {
        $detailPaket = $this->details->first()?->paket?->nama_paket;

        if (!empty($detailPaket)) {
            return $detailPaket;
        }

        return $this->pesananWebsite?->paket?->nama_paket ?? '-';
    }

    public function getTotalFormattedAttribute()
    {
        return 'Rp ' . number_format((float) $this->total_transaksi, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->job_status) {
            'Selesai' => 'success',
            'Booked'  => 'primary',
            'Batal'   => 'danger',
            default   => 'warning',
        };
    }

    /**
     * TERMIN HELPER METHODS
     */

    /**
     * Check apakah booking menggunakan 2 termin
     */
    public function isTwoInstallment(): bool
    {
        return $this->total_termin === 2;
    }

    /**
     * Check apakah booking menggunakan 3 termin
     */
    public function isThreeInstallment(): bool
    {
        return $this->total_termin === 3;
    }

    /**
     * Check apakah booking adalah all-in package
     */
    public function isAllInPackage(): bool
    {
        $paket = $this->details()->first()?->paket;
        return $paket && $paket->paket_type === 'all_in';
    }

    /**
     * Get termin yang belum dibayar
     */
    public function getUnpaidTerms()
    {
        return $this->paymentTerms()
            ->whereIn('payment_status', ['unpaid', 'overdue'])
            ->get();
    }

    /**
     * Get termin berikutnya yang belum dibayar
     */
    public function getNextDueTerm()
    {
        return $this->paymentTerms()
            ->whereIn('payment_status', ['unpaid', 'overdue'])
            ->orderBy('term_number')
            ->first();
    }

    /**
     * Get total pembayaran yang sudah diterima
     */
    public function getTotalPaid(): int
    {
        return (int) $this->paymentTerms()
            ->where('payment_status', 'paid')
            ->sum('paid_amount');
    }

    /**
     * Get total pembayaran yang masih harus dibayar
     */
    public function getTotalRemaining(): int
    {
        return $this->total_transaksi - $this->getTotalPaid();
    }

    /**
     * Check apakah semua termin sudah lunas
     */
    public function isFullyPaid(): bool
    {
        return $this->getTotalRemaining() <= 0;
    }

    /**
     * Check apakah booking pending (menunggu approval CEO)
     */
    public function isPending(): bool
    {
        return $this->booking_status === 'pending';
    }

    /**
     * Check apakah booking sudah di-approve (booked)
     */
    public function isBooked(): bool
    {
        return $this->booking_status === 'booked';
    }

    /**
     * Check apakah booking selesai (job completed)
     */
    public function isCompleted(): bool
    {
        return $this->booking_status === 'completed';
    }

    /**
     * Check apakah booking dibatalkan
     */
    public function isCancelled(): bool
    {
        return $this->booking_status === 'cancelled';
    }
}
