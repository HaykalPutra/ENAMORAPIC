<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Services\BookingStatusService;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
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
        return view('admin.pesanan.index', compact('bookings'));
    }

    public function show($id)
    {
        $this->bookingStatusService->syncPastBookedToCompleted();

        $booking = Booking::with(['details.paket', 'customer', 'pesananWebsite.paket'])->findOrFail($id);
        return view('admin.pesanan.show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'job_status'        => 'required|in:Pending,Booked,Selesai,Batal',
            'status_pembayaran' => 'required|in:DP,Lunas',
        ]);

        $booking = Booking::findOrFail($id);
        $newJobStatus = $request->job_status;

        // Keep booking_status in sync with CEO workflow.
        $bookingStatus = match ($newJobStatus) {
            'Booked' => 'booked',
            'Selesai' => 'completed',
            'Batal' => 'cancelled',
            default => 'pending',
        };

        $booking->update([
            'job_status'        => $newJobStatus,
            'booking_status'    => $bookingStatus,
            'status_pembayaran' => $request->status_pembayaran,
        ]);

        // Notify customer when CEO updates booking status.
        if ($booking->customer && $booking->customer->no_wa) {
            $subject = match ($newJobStatus) {
                'Booked' => 'Booking Anda Disetujui',
                'Batal' => 'Booking Anda Ditolak',
                'Selesai' => 'Booking Anda Selesai',
                default => 'Update Status Booking',
            };

            $message = match ($newJobStatus) {
                'Booked' => "Booking #{$booking->booking_id} telah disetujui. Tim kami akan menghubungi Anda untuk persiapan acara.",
                'Batal' => "Booking #{$booking->booking_id} saat ini tidak dapat diproses. Silakan hubungi admin untuk detail lebih lanjut.",
                'Selesai' => "Booking #{$booking->booking_id} telah ditandai selesai. Terima kasih telah menggunakan layanan Enamorapic.",
                default => "Status booking #{$booking->booking_id} diperbarui menjadi {$newJobStatus}.",
            };

            Notification::createNotification([
                'booking_id' => $booking->booking_id,
                'recipient_role' => 'CUSTOMER',
                'phone_number' => $booking->customer->no_wa,
                'notification_type' => $newJobStatus === 'Booked' ? 'booking_approved' : ($newJobStatus === 'Batal' ? 'booking_rejected' : 'general'),
                'subject' => $subject,
                'message' => $message,
                'channel' => 'whatsapp',
            ]);
        }

        return back()->with('success', 'Status booking berhasil diupdate.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->details()->delete();
        $booking->delete();
        return redirect()->route('admin.pesanan')->with('success', 'Booking berhasil dihapus.');
    }
}
