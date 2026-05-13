<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingStatusService;
use Illuminate\Http\Request;

class PesananAdmin2Controller extends Controller
{
    public function __construct(private BookingStatusService $bookingStatusService)
    {
    }

    public function index(Request $request)
    {
        $this->bookingStatusService->syncPastBookedToCompleted();

        $query = Booking::with(['details.paket', 'customer', 'pesananWebsite.paket']);

        if ($request->filled('status')) {
            $query->where('job_status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('nama_client', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tgl_acara', $request->bulan);
        }

        $bookings = $query->orderByDesc('booking_id')->paginate(20);
        return view('admin2.pesanan.index', compact('bookings'));
    }

    public function show($id)
    {
        $this->bookingStatusService->syncPastBookedToCompleted();

        $booking = Booking::with(['details.paket', 'customer', 'pesananWebsite.paket'])
            ->findOrFail($id);

        return view('admin2.pesanan.show', compact('booking'));
    }

    public function proses(Request $request, $id)
    {
        abort(403, 'Akses ditolak. Perubahan status pesanan hanya bisa dilakukan oleh CEO (admin).');
    }
}
