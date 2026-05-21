<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = now()->format('Y-m');

        $bookingBaseMonthQuery = DB::table('booking')
            ->whereRaw("DATE_FORMAT(tgl_booking,'%Y-%m') = ?", [$bulanIni])
            ->where('job_status', '!=', 'Batal');

        // Stat Cards
        $totalBulanIni   = DB::table('booking')->whereRaw("DATE_FORMAT(tgl_booking,'%Y-%m') = ?", [$bulanIni])->count();
        $totalPending    = DB::table('booking')->where('job_status','Pending')->count();
        $totalSelesai    = DB::table('booking')->where('job_status','Selesai')->count();

        // Revenue kartu utama: gabungan booking berstatus DP + Lunas (bulan ini)
        $revenueBulanIni = (float) (clone $bookingBaseMonthQuery)
            ->whereIn('status_pembayaran', ['DP', 'Lunas'])
            ->sum('total_transaksi');

        // Estimasi pendapatan: jika seluruh booking bulan ini lunas
        $potensiPendapatanBulanIni = (float) (clone $bookingBaseMonthQuery)->sum('total_transaksi');
        $lunasMasukBulanIni = (float) (clone $bookingBaseMonthQuery)
            ->where('status_pembayaran', 'Lunas')
            ->sum('total_transaksi');
        $estimasiSisaPendapatanBulanIni = max(0, $potensiPendapatanBulanIni - $lunasMasukBulanIni);

        // Booking terbaru (10 records)
        $bookingTerbaru = DB::table('booking')
            ->join('customer','booking.customer_id','=','customer.customer_id')
            ->select('booking.*','customer.nama_client')
            ->orderBy('tgl_booking','desc')
            ->limit(10)
            ->get();

        // Line chart - 6 bulan terakhir (lunas masuk vs potensi jika semua lunas)
        $months6   = [];
        $lunas6 = [];
        $potensi6 = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $months6[]   = $m->locale('id')->isoFormat('MMM Y');

            $potensi6[] = (float) DB::table('booking')
                ->whereRaw("DATE_FORMAT(tgl_booking,'%Y-%m') = ?", [$m->format('Y-m')])
                ->where('job_status','!=','Batal')
                ->sum('total_transaksi');

            $lunas6[] = (float) DB::table('booking')
                ->whereRaw("DATE_FORMAT(tgl_booking,'%Y-%m') = ?", [$m->format('Y-m')])
                ->where('job_status','!=','Batal')
                ->where('status_pembayaran', 'Lunas')
                ->sum('total_transaksi');
        }

        // Bar chart - yearly
        $yearlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $yearlyData[] = (float) DB::table('booking')
                ->whereMonth('tgl_booking', $m)
                ->whereYear('tgl_booking', now()->year)
                ->where('job_status','!=','Batal')
                ->sum('total_transaksi');
        }

        // Doughnut - payment status
        $lunas = DB::table('booking')->where('status_pembayaran','Lunas')->count();
        $dp    = DB::table('booking')->where('status_pembayaran','DP')->count();

        // Paket terpopuler
        $paketPopuler = DB::table('booking_detail')
            ->join('paket','booking_detail.paket_id','=','paket.paket_id')
            ->select('paket.nama_paket', DB::raw('COUNT(booking_detail.paket_id) as total'))
            ->groupBy('booking_detail.paket_id','paket.nama_paket')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Badge notif navbar
        $jumlahPesananBaru = DB::table('pesanan_website')->where('status','pending')->count();

        return view('admin.dashboard.index', compact(
            'totalBulanIni','totalPending','totalSelesai','revenueBulanIni',
            'bookingTerbaru','months6','lunas6','potensi6','yearlyData',
            'lunas','dp','paketPopuler','jumlahPesananBaru',
            'potensiPendapatanBulanIni','lunasMasukBulanIni','estimasiSisaPendapatanBulanIni'
        ));
    }

    public function insights(Request $request)
    {
        $data = $this->buildInsightsData($request);

        return view('admin.insights.index', $data);
    }

    public function exportInsightsPdf(Request $request)
    {
        $data = $this->buildInsightsData($request);

        $pdf = Pdf::loadView('admin.insights.pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->stream('insights-ceo-' . now()->format('Ymd') . '.pdf');
    }

    public function exportInsightsExcel(Request $request)
    {
        $data = $this->buildInsightsData($request);

        $filename = 'insights-ceo-' . now()->format('Ymd_His') . '.csv';

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
