<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Freelance;
use App\Models\Lead;
use App\Models\Pegawai;
use App\Models\PesananWebsite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function insights(Request $request)
    {
        $data = $this->buildInsightsData($request);

        return view('admin2.insights.index', $data);
    }

    public function exportInsightsPdf(Request $request)
    {
        $data = $this->buildInsightsData($request);

        $pdf = Pdf::loadView('admin2.insights.pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->download('insights-admin2-' . now()->format('Ymd') . '.pdf');
    }

    public function exportInsightsExcel(Request $request)
    {
        $data = $this->buildInsightsData($request);

        $filename = 'insights-admin2-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['Executive Insights']);
            fputcsv($out, []);
            fputcsv($out, ['Total Booking', $data['totalBooking']]);
            fputcsv($out, ['DP', $data['dpCount']]);
            fputcsv($out, ['Lunas', $data['lunasCount']]);
            fputcsv($out, ['Pendapatan Bulan Ini', (int) $data['revenueThisMonth']]);
            fputcsv($out, ['Conversion Rate', $data['conversionRate'] . '%']);
            fputcsv($out, ['Average Pelunasan (hari)', $data['avgPelunasanDays']]);
            fputcsv($out, []);

            fputcsv($out, ['Trend Booking']);
            fputcsv($out, ['Bulan', 'Jumlah Booking']);
            foreach ($data['months'] as $idx => $label) {
                fputcsv($out, [$label, $data['bookingTrend'][$idx] ?? 0]);
            }
            fputcsv($out, []);

            fputcsv($out, ['Trend Pendapatan']);
            fputcsv($out, ['Bulan', 'Pendapatan']);
            foreach ($data['months'] as $idx => $label) {
                fputcsv($out, [$label, (int) ($data['revenueTrend'][$idx] ?? 0)]);
            }
            fputcsv($out, []);

            fputcsv($out, ['Lead per Status']);
            foreach ($data['leadStatuses'] as $key => $label) {
                fputcsv($out, [$label, $data['leadStatusCounts'][$key] ?? 0]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function buildInsightsData(Request $request): array
    {
        $range = (int) $request->get('range', 6);
        if (!in_array($range, [3, 6, 12], true)) {
            $range = 6;
        }

        $startInput = (string) $request->get('start_date', '');
        $endInput = (string) $request->get('end_date', '');

        $startDate = $startInput !== '' ? Carbon::parse($startInput)->startOfDay() : null;
        $endDate = $endInput !== '' ? Carbon::parse($endInput)->endOfDay() : null;

        if ($startDate && $endDate && $startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        if (!$startDate || !$endDate) {
            $startDate = now()->subMonths($range - 1)->startOfMonth();
            $endDate = now()->endOfMonth();
        }

        $revenueSource = $request->get('revenue_source', 'payment');
        if (!in_array($revenueSource, ['payment', 'booking'], true)) {
            $revenueSource = 'payment';
        }

        $months = [];
        $bookingTrend = [];
        $revenueTrend = [];
        $cursor = $startDate->copy()->startOfMonth();
        $endMonth = $endDate->copy()->startOfMonth();

        while ($cursor->lte($endMonth)) {
            $months[] = $cursor->locale('id')->isoFormat('MMM Y');

            $bookingTrend[] = (int) DB::table('booking')
                ->where('job_status', '!=', 'Batal')
                ->whereYear('tgl_booking', $cursor->year)
                ->whereMonth('tgl_booking', $cursor->month)
                ->count();

            if ($revenueSource === 'booking') {
                $revenueTrend[] = (float) DB::table('booking')
                    ->where('job_status', '!=', 'Batal')
                    ->whereYear('tgl_booking', $cursor->year)
                    ->whereMonth('tgl_booking', $cursor->month)
                    ->sum('total_transaksi');
            } else {
                $revenueTrend[] = (float) DB::table('payment_logs')
                    ->where('payment_status', 'success')
                    ->whereYear('created_at', $cursor->year)
                    ->whereMonth('created_at', $cursor->month)
                    ->sum('amount');
            }

            $cursor->addMonth();
        }

        $bookingQuery = DB::table('booking')
            ->where('job_status', '!=', 'Batal')
            ->whereBetween('tgl_booking', [$startDate->toDateString(), $endDate->toDateString()]);

        $totalBooking = (int) (clone $bookingQuery)->count();
        $dpCount = (int) (clone $bookingQuery)->where('status_pembayaran', 'DP')->count();
        $lunasCount = (int) (clone $bookingQuery)->where('status_pembayaran', 'Lunas')->count();

        if ($revenueSource === 'booking') {
            $revenueThisMonth = (float) (clone $bookingQuery)->sum('total_transaksi');
        } else {
            $revenueThisMonth = (float) DB::table('payment_logs')
                ->where('payment_status', 'success')
                ->whereBetween('created_at', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
                ->sum('amount');
        }

        $paketPopuler = DB::table('booking_detail')
            ->join('booking', 'booking_detail.booking_id', '=', 'booking.booking_id')
            ->join('paket', 'booking_detail.paket_id', '=', 'paket.paket_id')
            ->where('booking.job_status', '!=', 'Batal')
            ->whereBetween('booking.tgl_booking', [$startDate->toDateString(), $endDate->toDateString()])
            ->select('paket.nama_paket', DB::raw('COUNT(booking_detail.paket_id) as total'))
            ->groupBy('booking_detail.paket_id', 'paket.nama_paket')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $leadQuery = Lead::query()
            ->whereBetween('created_at', [$startDate->toDateTimeString(), $endDate->toDateTimeString()]);

        $totalLeads = (int) (clone $leadQuery)->count();
        $convertedLeads = (int) (clone $leadQuery)->whereIn('status', ['booked', 'completed'])->count();
        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 1) : 0.0;

        $leadStatusCounts = (clone $leadQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $funnelLabels = ['Prospek', 'Negosiasi', 'Booking', 'Selesai'];
        $funnelValues = [
            (int) ($leadStatusCounts['prospect'] ?? 0),
            (int) ($leadStatusCounts['negotiation'] ?? 0),
            (int) ($leadStatusCounts['booked'] ?? 0),
            (int) ($leadStatusCounts['completed'] ?? 0),
        ];

        $paidSummaries = DB::table('payment_terms')
            ->join('booking', 'payment_terms.booking_id', '=', 'booking.booking_id')
            ->select(
                'booking.booking_id',
                'booking.tgl_booking',
                DB::raw('MAX(payment_terms.paid_at) as last_paid_at'),
                DB::raw('SUM(CASE WHEN payment_terms.payment_status = "paid" THEN 1 ELSE 0 END) as paid_terms'),
                DB::raw('COUNT(payment_terms.id) as total_terms')
            )
            ->whereNotNull('payment_terms.paid_at')
            ->whereBetween('booking.tgl_booking', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('booking.booking_id', 'booking.tgl_booking')
            ->havingRaw('paid_terms = total_terms')
            ->get();

        $avgPelunasanDays = 0;
        if ($paidSummaries->isNotEmpty()) {
            $totalDays = 0;
            foreach ($paidSummaries as $row) {
                $start = Carbon::parse($row->tgl_booking);
                $end = Carbon::parse($row->last_paid_at);
                $totalDays += max(0, $start->diffInDays($end));
            }
            $avgPelunasanDays = round($totalDays / $paidSummaries->count(), 1);
        }

        return [
            'range' => $range,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'revenueSource' => $revenueSource,
            'months' => $months,
            'bookingTrend' => $bookingTrend,
            'revenueTrend' => $revenueTrend,
            'totalBooking' => $totalBooking,
            'dpCount' => $dpCount,
            'lunasCount' => $lunasCount,
            'revenueThisMonth' => $revenueThisMonth,
            'paketPopuler' => $paketPopuler,
            'conversionRate' => $conversionRate,
            'avgPelunasanDays' => $avgPelunasanDays,
            'leadStatusCounts' => $leadStatusCounts,
            'leadStatuses' => Lead::STATUSES,
            'funnelLabels' => $funnelLabels,
            'funnelValues' => $funnelValues,
        ];
    }
}
