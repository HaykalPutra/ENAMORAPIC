<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bookings = $this->buildReportQuery($request)
            ->orderBy('tgl_booking')
            ->get()
            ->map(fn (Booking $booking) => $this->enrichBookingFinancial($booking));

        $totalPendapatanMasuk = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->sum('nominal_masuk');

        $totalPendapatanDp = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->where('status_pembayaran', 'DP')
            ->sum('nominal_masuk');

        $totalEstimasi = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->sum('total_transaksi');

        $totalSisa = max(0, $totalEstimasi - $totalPendapatanMasuk);

        return view('admin.laporan.index', compact(
            'bookings',
            'totalPendapatanMasuk',
            'totalPendapatanDp',
            'totalEstimasi',
            'totalSisa'
        ));
    }

    public function exportPdf(Request $request)
    {
        $bookings = $this->buildReportQuery($request)
            ->orderBy('tgl_booking')
            ->get()
            ->map(fn (Booking $booking) => $this->enrichBookingFinancial($booking));

        $totalPendapatanMasuk = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->sum('nominal_masuk');

        $totalPendapatanDp = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->where('status_pembayaran', 'DP')
            ->sum('nominal_masuk');

        $totalEstimasi = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->sum('total_transaksi');

        $totalSisa = max(0, $totalEstimasi - $totalPendapatanMasuk);

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'bookings',
            'totalPendapatanMasuk',
            'totalPendapatanDp',
            'totalEstimasi',
            'totalSisa'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Enamora_'.date('Ymd').'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $bookings = $this->buildReportQuery($request)
            ->orderBy('tgl_booking')
            ->get()
            ->map(fn (Booking $booking) => $this->enrichBookingFinancial($booking));

        $filename = 'laporan-keuangan-ceo-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($bookings) {
            $out = fopen('php://output', 'w');

            // BOM UTF-8 agar karakter tampil benar di Excel.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, [
                'No',
                'Tanggal Transaksi',
                'Nama Client',
                'Paket',
                'Tanggal Acara',
                'Status Bayar',
                'Status Acara',
                'Total Booking',
                'Nominal Masuk',
                'Sisa Tagihan',
            ]);

            foreach ($bookings->values() as $index => $booking) {
                fputcsv($out, [
                    $index + 1,
                    optional($booking->tgl_booking)->format('Y-m-d'),
                    $booking->nama_client,
                    $booking->nama_paket,
                    optional($booking->tgl_acara)->format('Y-m-d'),
                    $booking->status_pembayaran,
                    $booking->job_status,
                    (int) $booking->total_transaksi,
                    (int) $booking->nominal_masuk,
                    (int) $booking->sisa_tagihan,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function invoice(Booking $booking)
    {
        $booking->load(['details.paket', 'paymentTerms', 'customer']);
        $invoice = $this->buildInvoiceData($booking);

        return view('shared.invoice.show', [
            'booking' => $booking,
            'invoice' => $invoice,
            'pdfRoute' => route('admin.laporan.invoice.pdf', $booking->booking_id),
            'backRoute' => route('admin.laporan'),
            'roleLabel' => 'CEO',
        ]);
    }

    public function invoicePdf(Booking $booking)
    {
        $booking->load(['details.paket', 'paymentTerms', 'customer']);
        $invoice = $this->buildInvoiceData($booking);

        $pdf = Pdf::loadView('shared.invoice.pdf', [
            'booking' => $booking,
            'invoice' => $invoice,
            'roleLabel' => 'CEO',
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('invoice-booking-' . $booking->booking_id . '.pdf');
    }

    private function buildReportQuery(Request $request)
    {
        $tglDari = $request->get('tgl_dari', date('Y-01-01'));
        $tglSampai = $request->get('tgl_sampai', date('Y-12-31'));
        $statusBayar = $request->get('status_bayar', 'All');
        $filterBayar = $request->get('filter_bayar', 'All'); // DP, Lunas, All

        $query = Booking::with(['details.paket', 'paymentTerms'])
            ->whereBetween('tgl_booking', [$tglDari, $tglSampai]);

        if ($statusBayar !== 'All') {
            $query->where('status_pembayaran', $statusBayar);
        }

        // Filter pembayaran baru
        if ($filterBayar === 'DP') {
            $query->where('status_pembayaran', 'DP');
        } elseif ($filterBayar === 'Lunas') {
            $query->where('status_pembayaran', 'Lunas');
        }

        return $query;
    }

    private function buildInvoiceData(Booking $booking): array
    {
        $nominalMasuk = $this->calculateNominalMasuk($booking);
        $totalTransaksi = (int) $booking->total_transaksi;
        $sisaTagihan = max(0, $totalTransaksi - $nominalMasuk);
        $paket = $booking->details->first()?->paket?->nama_paket ?? '-';

        return [
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . $booking->booking_id,
            'issued_at' => now(),
            'paket' => $paket,
            'total_transaksi' => $totalTransaksi,
            'nominal_masuk' => $nominalMasuk,
            'sisa_tagihan' => $sisaTagihan,
            'status_pembayaran' => $booking->status_pembayaran,
            'customer_name' => $booking->nama_client,
            'event_date' => $booking->tgl_acara,
        ];
    }

    private function enrichBookingFinancial(Booking $booking): Booking
    {
        $nominalMasuk = $this->calculateNominalMasuk($booking);
        $total = (int) $booking->total_transaksi;

        $booking->setAttribute('nominal_masuk', $nominalMasuk);
        $booking->setAttribute('sisa_tagihan', max(0, $total - $nominalMasuk));
        $booking->setAttribute('nama_paket', $booking->details->first()?->paket?->nama_paket ?? '-');

        return $booking;
    }

    private function calculateNominalMasuk(Booking $booking): int
    {
        if ($booking->job_status === 'Batal') {
            return 0;
        }

        $totalTransaksi = (int) $booking->total_transaksi;
        $statusPembayaran = strtoupper((string) $booking->status_pembayaran);
        $totalPaidTerms = (int) $booking->paymentTerms
            ->where('payment_status', 'paid')
            ->sum('paid_amount');

        if ($statusPembayaran === 'LUNAS') {
            if ($totalPaidTerms <= 0) {
                return $totalTransaksi;
            }

            return min($totalPaidTerms, $totalTransaksi);
        }

        if ($statusPembayaran === 'DP') {
            if ($totalPaidTerms > 0) {
                return min($totalPaidTerms, $totalTransaksi);
            }

            // Fallback ke DP standar 30% ketika term paid belum tersedia.
            return (int) round($totalTransaksi * 0.30);
        }

        return min(max(0, $totalPaidTerms), $totalTransaksi);
    }
}