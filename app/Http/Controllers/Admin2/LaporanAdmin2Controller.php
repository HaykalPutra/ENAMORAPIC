<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanAdmin2Controller extends Controller
{
    public function index(Request $request)
    {
        $bookings = $this->buildReportQuery($request)
            ->orderBy('tgl_acara')
            ->get()
            ->map(fn (Booking $booking) => $this->enrichBookingFinancial($booking));

        $totalPendapatan = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->sum('nominal_masuk');

        $totalPendapatanDp = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->where('status_pembayaran', 'DP')
            ->sum('nominal_masuk');

        $totalEstimasi = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->sum('total_transaksi');

        $totalSisa = max(0, $totalEstimasi - $totalPendapatan);

        return view('admin2.laporan.index', compact('bookings', 'totalPendapatan', 'totalPendapatanDp', 'totalEstimasi', 'totalSisa'));
    }

    public function exportPdf(Request $request)
    {
        $bookings = $this->buildReportQuery($request)
            ->orderBy('tgl_acara')
            ->get()
            ->map(fn (Booking $booking) => $this->enrichBookingFinancial($booking));

        $totalPendapatan = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->sum('nominal_masuk');

        $totalPendapatanDp = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->where('status_pembayaran', 'DP')
            ->sum('nominal_masuk');

        $totalEstimasi = (int) $bookings
            ->where('job_status', '!=', 'Batal')
            ->sum('total_transaksi');

        $totalSisa = max(0, $totalEstimasi - $totalPendapatan);

        $pdf = Pdf::loadView('admin2.laporan.pdf', compact('bookings', 'totalPendapatan', 'totalPendapatanDp', 'totalEstimasi', 'totalSisa'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-admin-enamorapic-' . now()->format('Y-m') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $bookings = $this->buildReportQuery($request)
            ->orderBy('tgl_acara')
            ->get()
            ->map(fn (Booking $booking) => $this->enrichBookingFinancial($booking));

        $filename = 'laporan-keuangan-sekretaris-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($bookings) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, [
                'No',
                'Tanggal Booking',
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
            'pdfRoute' => route('admin2.laporan.invoice.pdf', $booking->booking_id),
            'backRoute' => route('admin2.laporan'),
            'roleLabel' => 'Sekretaris',
        ]);
    }

    public function invoicePdf(Booking $booking)
    {
        $booking->load(['details.paket', 'paymentTerms', 'customer']);
        $invoice = $this->buildInvoiceData($booking);

        $pdf = Pdf::loadView('shared.invoice.pdf', [
            'booking' => $booking,
            'invoice' => $invoice,
            'roleLabel' => 'Sekretaris',
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('invoice-booking-' . $booking->booking_id . '.pdf');
    }

    private function buildReportQuery(Request $request)
    {
        $query = Booking::with(['details.paket', 'paymentTerms']);

        // Filter by event date range (dari dashboard calendar click)
        if ($request->filled('tgl_acara_dari') && $request->filled('tgl_acara_sampai')) {
            $query->whereBetween('tgl_acara', [$request->tgl_acara_dari, $request->tgl_acara_sampai]);
        } else {
            // Default filter by bulan/tahun
            if ($request->filled('bulan')) {
                $query->whereMonth('tgl_acara', $request->bulan);
            }

            if ($request->filled('tahun')) {
                $query->whereYear('tgl_acara', $request->tahun);
            } else {
                $query->whereYear('tgl_acara', now()->year);
            }
        }

        // Filter pembayaran: DP, Lunas, All
        $filterBayar = $request->get('filter_bayar', 'All');
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

            return (int) round($totalTransaksi * 0.30);
        }

        return min(max(0, $totalPaidTerms), $totalTransaksi);
    }
}
