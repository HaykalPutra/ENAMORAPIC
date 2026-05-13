<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PaymentTerm;
use Carbon\Carbon;

class PaymentTermService
{
    /**
     * Generate payment terms schedule untuk booking
     * 
     * Logika:
     * - 2 Termin (Wedding Only / Prewedd Only):
     *   - Termin 1: 30% (due date: tanggal booking)
     *   - Termin 2: 70% (due date: H-1 sebelum acara)
     * 
     * - 3 Termin (All In Package):
     *   - Termin 1: 30% (due date: tanggal booking)
     *   - Termin 2: 30% (due date: H-1 prewedd)
     *   - Termin 3: 40% (due date: H-1 wedding)
     */
    public static function generatePaymentTerms(Booking $booking): array
    {
        $totalAmount = $booking->total_transaksi;
        $tglBooking = $booking->tgl_booking;
        $tglAcara = Carbon::parse($booking->tgl_acara);
        $tglPrewedd = $booking->tgl_prewedd ? Carbon::parse($booking->tgl_prewedd) : null;
        $terms = [];

        if ($booking->isTwoInstallment()) {
            // 2 Termin: 30% dan 70%
            $terms = [
                [
                    'booking_id' => $booking->booking_id,
                    'term_number' => 1,
                    'term_percentage' => 30,
                    'term_amount' => (int) ((float) $totalAmount * 0.30),
                    'due_date' => $tglBooking, // Langsung at booking
                    'payment_status' => 'unpaid',
                ],
                [
                    'booking_id' => $booking->booking_id,
                    'term_number' => 2,
                    'term_percentage' => 70,
                    'term_amount' => (int) ((float) $totalAmount * 0.70),
                    'due_date' => $tglAcara->copy()->subDay(), // H-1 sebelum acara
                    'payment_status' => 'unpaid',
                ],
            ];
        } elseif ($booking->isThreeInstallment()) {
            // 3 Termin: 30%, 30%, 40%
            $terms = [
                [
                    'booking_id' => $booking->booking_id,
                    'term_number' => 1,
                    'term_percentage' => 30,
                    'term_amount' => (int) ((float) $totalAmount * 0.30),
                    'due_date' => $tglBooking, // Langsung at booking
                    'payment_status' => 'unpaid',
                ],
                [
                    'booking_id' => $booking->booking_id,
                    'term_number' => 2,
                    'term_percentage' => 30,
                    'term_amount' => (int) ((float) $totalAmount * 0.30),
                    'due_date' => $tglPrewedd ? $tglPrewedd->copy()->subDay() : $tglAcara->copy()->subDay(), // H-1 prewedd
                    'payment_status' => 'unpaid',
                ],
                [
                    'booking_id' => $booking->booking_id,
                    'term_number' => 3,
                    'term_percentage' => 40,
                    'term_amount' => (int) ((float) $totalAmount * 0.40),
                    'due_date' => $tglAcara->copy()->subDay(), // H-1 wedding
                    'payment_status' => 'unpaid',
                ],
            ];
        }

        return $terms;
    }

    /**
     * Create payment terms di database
     */
    public static function createPaymentTerms(Booking $booking): void
    {
        // Delete existing terms jika ada (fallback update)
        $booking->paymentTerms()->delete();

        // Generate dan create new terms
        $terms = self::generatePaymentTerms($booking);
        foreach ($terms as $term) {
            PaymentTerm::create($term);
        }
    }

    /**
     * Update termin menjadi overdue jika past due date
     */
    public static function updateOverdueTerms(): void
    {
        $unpaidTerms = PaymentTerm::where('payment_status', 'unpaid')
            ->whereDate('due_date', '<', now())
            ->get();

        foreach ($unpaidTerms as $term) {
            $term->update(['payment_status' => 'overdue']);
        }
    }

    /**
     * Get payment summary untuk booking
     */
    public static function getPaymentSummary(Booking $booking): array
    {
        $terms = $booking->paymentTerms()->get();
        
        return [
            'total_amount' => $booking->total_transaksi,
            'total_paid' => $terms->where('payment_status', 'paid')->sum('paid_amount'),
            'total_remaining' => $booking->getTotalRemaining(),
            'terms_count' => $booking->total_termin,
            'paid_terms' => $terms->where('payment_status', 'paid')->count(),
            'unpaid_terms' => $terms->whereIn('payment_status', ['unpaid', 'overdue'])->count(),
            'overdue_terms' => $terms->where('payment_status', 'overdue')->count(),
            'is_fully_paid' => $booking->isFullyPaid(),
        ];
    }

    /**
     * Get payment schedule dalam format user-friendly
     */
    public static function getPaymentScheduleFormatted(Booking $booking): array
    {
        $terms = $booking->paymentTerms()
            ->orderBy('term_number')
            ->get();

        return $terms->map(function ($term) {
            return [
                'term_number' => $term->term_number,
                'percentage' => $term->term_percentage . '%',
                'amount' => 'Rp ' . number_format($term->term_amount, 0, ',', '.'),
                'due_date' => $term->due_date->format('d M Y'),
                'status' => ucfirst($term->payment_status),
                'status_badge' => match($term->payment_status) {
                    'paid' => 'success',
                    'overdue' => 'danger',
                    default => 'warning',
                },
            ];
        })->toArray();
    }
}
