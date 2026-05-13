<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Services\PaymentTermService;
use Carbon\Carbon;
use Tests\TestCase;

class PaymentTermServiceTest extends TestCase
{
    public function test_generate_two_installment_schedule(): void
    {
        $booking = new Booking([
            'booking_id' => 100,
            'total_transaksi' => 10000000,
            'total_termin' => 2,
            'tgl_booking' => Carbon::parse('2026-03-10'),
            'tgl_acara' => Carbon::parse('2026-03-20'),
        ]);

        $terms = PaymentTermService::generatePaymentTerms($booking);

        $this->assertCount(2, $terms);
        $this->assertSame(30, $terms[0]['term_percentage']);
        $this->assertSame(3000000, $terms[0]['term_amount']);
        $this->assertSame('2026-03-10', Carbon::parse($terms[0]['due_date'])->toDateString());

        $this->assertSame(70, $terms[1]['term_percentage']);
        $this->assertSame(7000000, $terms[1]['term_amount']);
        $this->assertSame('2026-03-19', Carbon::parse($terms[1]['due_date'])->toDateString());
    }

    public function test_generate_three_installment_schedule(): void
    {
        $booking = new Booking([
            'booking_id' => 101,
            'total_transaksi' => 10000000,
            'total_termin' => 3,
            'tgl_booking' => Carbon::parse('2026-03-10'),
            'tgl_prewedd' => Carbon::parse('2026-03-15'),
            'tgl_acara' => Carbon::parse('2026-03-20'),
        ]);

        $terms = PaymentTermService::generatePaymentTerms($booking);

        $this->assertCount(3, $terms);
        $this->assertSame(30, $terms[0]['term_percentage']);
        $this->assertSame(3000000, $terms[0]['term_amount']);
        $this->assertSame('2026-03-10', Carbon::parse($terms[0]['due_date'])->toDateString());

        $this->assertSame(30, $terms[1]['term_percentage']);
        $this->assertSame(3000000, $terms[1]['term_amount']);
        $this->assertSame('2026-03-14', Carbon::parse($terms[1]['due_date'])->toDateString());

        $this->assertSame(40, $terms[2]['term_percentage']);
        $this->assertSame(4000000, $terms[2]['term_amount']);
        $this->assertSame('2026-03-19', Carbon::parse($terms[2]['due_date'])->toDateString());
    }
}
