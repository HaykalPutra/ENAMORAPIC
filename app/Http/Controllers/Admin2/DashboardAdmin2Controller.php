<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Freelance;
use App\Models\Pegawai;
use App\Models\PesananWebsite;
use Carbon\Carbon;

class DashboardAdmin2Controller extends Controller
{
    public function index()
    {
        $totalPegawai    = Pegawai::count();
        $totalFreelance  = Freelance::count();
        $pesananPending  = PesananWebsite::where('status', 'pending')->count();
        $bookingBulanIni = Booking::whereMonth('tgl_booking', now()->month)
                                  ->whereYear('tgl_booking', now()->year)
                                  ->count();

        $bookingTerbaru  = Booking::with(['details.paket'])
                                  ->orderByDesc('booking_id')
                                  ->limit(8)
                                  ->get();

        // Calendar & Wedding Data untuk bulan ini
        $currentMonth = now();
        $weddingDates = Booking::with(['details'])
            ->whereMonth('tgl_acara', $currentMonth->month)
            ->whereYear('tgl_acara', $currentMonth->year)
            ->where('job_status', '!=', 'Batal')
            ->get()
            ->groupBy(fn ($b) => Carbon::parse($b->tgl_acara)->day);

        // DP Follow Up (status pembayaran = 'DP' dan ada termin yang belum paid)
        $dpFollowUp = Booking::with(['customer', 'paymentTerms', 'details.paket'])
            ->where('status_pembayaran', 'DP')
            ->where('job_status', '!=', 'Batal')
            ->whereMonth('tgl_acara', $currentMonth->month)
            ->whereYear('tgl_acara', $currentMonth->year)
            ->get()
            ->filter(function ($booking) {
                $unpaidTerms = $booking->paymentTerms->where('payment_status', '!=', 'paid')->count();
                return $unpaidTerms > 0;
            })
            ->values();

        $calendarDays = $this->generateCalendarDays($currentMonth, $weddingDates);

        return view('admin2.dashboard', compact(
            'totalPegawai', 'totalFreelance', 'pesananPending',
            'bookingBulanIni', 'bookingTerbaru', 'currentMonth',
            'weddingDates', 'dpFollowUp', 'calendarDays'
        ));
    }

    private function generateCalendarDays($month, $weddingDates): array
    {
        $firstDay = $month->copy()->startOfMonth();
        $lastDay = $month->copy()->endOfMonth();
        $days = [];

        // Days dari bulan sebelumnya
        $startDayOfWeek = $firstDay->dayOfWeek;
        if ($startDayOfWeek > 0) {
            $prevMonth = $firstDay->copy()->subDays($startDayOfWeek);
            for ($i = 0; $i < $startDayOfWeek; $i++) {
                $days[] = [
                    'day' => $prevMonth->day,
                    'date' => $prevMonth->copy(),
                    'isCurrentMonth' => false,
                    'hasWedding' => false,
                ];
                $prevMonth->addDay();
            }
        }

        // Days dari bulan ini
        $currentDay = $firstDay->copy();
        while ($currentDay <= $lastDay) {
            $dayNum = $currentDay->day;
            $hasWedding = isset($weddingDates[$dayNum]);
            $days[] = [
                'day' => $dayNum,
                'date' => $currentDay->copy(),
                'isCurrentMonth' => true,
                'hasWedding' => $hasWedding,
                'weddingCount' => $hasWedding ? $weddingDates[$dayNum]->count() : 0,
            ];
            $currentDay->addDay();
        }

        // Days dari bulan berikutnya
        $remainingDays = 42 - count($days);
        $nextMonth = $lastDay->copy()->addDay();
        for ($i = 0; $i < $remainingDays; $i++) {
            $days[] = [
                'day' => $nextMonth->day,
                'date' => $nextMonth->copy(),
                'isCurrentMonth' => false,
                'hasWedding' => false,
            ];
            $nextMonth->addDay();
        }

        return $days;
    }
}
