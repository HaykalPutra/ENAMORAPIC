<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('booking')
            ->join('customer','booking.customer_id','=','customer.customer_id')
            ->leftJoin('booking_detail','booking.booking_id','=','booking_detail.booking_id')
            ->leftJoin('paket','booking_detail.paket_id','=','paket.paket_id')
            ->select('booking.*','customer.nama_client','customer.no_wa','paket.nama_paket','paket.kategori');

        // Filter status
        if ($request->filled('status')) {
            $query->where('booking.job_status', $request->status);
        }
        // Filter tanggal
        if ($request->filled('dari')) {
            $query->where('booking.tgl_acara', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->where('booking.tgl_acara', '<=', $request->sampai);
        }

        $bookings = $query->orderByDesc('booking.tgl_booking')->paginate(12);

        $jumlahPesananBaru = DB::table('pesanan_website')->where('status','pending')->count();

        return view('admin.booking.index', compact('bookings','jumlahPesananBaru'));
    }

    public function update(Request $request, $id)
    {
        // Sama persis dengan booking_update.php
        DB::table('booking')->where('booking_id', $id)->update([
            'status_pembayaran' => $request->status_pembayaran,
            'job_status'        => $request->job_status,
            'booking_status'    => match ($request->job_status) {
                'Booked' => 'booked',
                'Selesai' => 'completed',
                'Batal' => 'cancelled',
                default => 'pending',
            },
        ]);

        return redirect()->route('admin.booking.index')
                         ->with('success','Status booking berhasil diupdate!');
    }
}
