<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
}
