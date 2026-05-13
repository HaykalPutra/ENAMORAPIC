<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Customer;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file_csv');
        $rows = array_map('str_getcsv', file($file->getPathname()));
        $header = array_shift($rows); // Skip header row

        $imported = 0;
        $errors   = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $i => $row) {
                if (count($row) < 5) continue;

                [$nama_client, $tgl_acara, $nama_paket, $status_bayar, $job_status] = $row;

                // Cari paket
                $paket = Paket::where('nama_paket', trim($nama_paket))->first();
                if (!$paket) {
                    $errors[] = "Baris " . ($i + 2) . ": Paket '$nama_paket' tidak ditemukan.";
                    continue;
                }

                // Buat/ambil customer
                $customer = Customer::firstOrCreate(
                    ['nama_client' => trim($nama_client)],
                    ['no_wa' => '-', 'alamat' => 0]
                );

                // Simpan booking
                $booking = Booking::create([
                    'customer_id'       => $customer->customer_id,
                    'nama_client'       => trim($nama_client),
                    'tgl_booking'       => now()->toDateString(),
                    'tgl_acara'         => trim($tgl_acara),
                    'total_transaksi'   => $paket->harga,
                    'status_pembayaran' => trim($status_bayar),
                    'job_status'        => trim($job_status),
                    'booking_status'    => match (trim($job_status)) {
                        'Booked' => 'booked',
                        'Selesai' => 'completed',
                        'Batal' => 'cancelled',
                        default => 'pending',
                    },
                ]);

                BookingDetail::create([
                    'booking_id'     => $booking->booking_id,
                    'paket_id'       => $paket->paket_id,
                    'harga_saat_ini' => $paket->harga,
                ]);

                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }

        $msg = "Berhasil import $imported data.";
        if ($errors) {
            $msg .= ' Beberapa baris dilewati: ' . implode(', ', $errors);
        }

        return back()->with('success', $msg);
    }
}
