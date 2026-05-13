<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\PesananWebsite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DataPesananController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = (string) $request->get('status', 'pending');
        $date = (string) $request->get('date', '');

        $pesananQuery = PesananWebsite::with(['paket', 'booking.customer'])
            ->orderByDesc('pesanan_id');

        if (in_array($status, ['pending', 'done'], true)) {
            $pesananQuery->where('status', $status);
        }

        if ($search !== '') {
            $pesananQuery->where(function ($q) use ($search) {
                $q->where('nama_pemesan', 'like', "%{$search}%")
                    ->orWhere('no_wa', 'like', "%{$search}%")
                    ->orWhere('booking_id', 'like', "%{$search}%");
            });
        }

        if ($date !== '') {
            $pesananQuery->whereDate('tanggal_booking', $date);
        }

        $pesanans = $pesananQuery->paginate(12)->appends($request->query());

        $today = now()->toDateString();
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $table = 'pesanan_website';
        $hasUpdatedAt = Schema::hasColumn($table, 'updated_at');
        $hasCreatedAt = Schema::hasColumn($table, 'created_at');
        $hasTanggalBooking = Schema::hasColumn($table, 'tanggal_booking');

        $validatedTodayQuery = PesananWebsite::where('status', 'done');
        if ($hasUpdatedAt) {
            $validatedTodayQuery->whereDate('updated_at', $today);
        } elseif ($hasCreatedAt) {
            $validatedTodayQuery->whereDate('created_at', $today);
        } elseif ($hasTanggalBooking) {
            $validatedTodayQuery->whereDate('tanggal_booking', $today);
        }

        $newTodayQuery = PesananWebsite::query();
        if ($hasCreatedAt) {
            $newTodayQuery->whereDate('created_at', $today);
        } elseif ($hasTanggalBooking) {
            $newTodayQuery->whereDate('tanggal_booking', $today);
        }

        $weekTotalQuery = PesananWebsite::query();
        if ($hasCreatedAt) {
            $weekTotalQuery->whereBetween('created_at', [$weekStart, $weekEnd]);
        } elseif ($hasTanggalBooking) {
            $weekTotalQuery->whereBetween('tanggal_booking', [$weekStart, $weekEnd]);
        }

        $kpis = [
            'pending' => PesananWebsite::where('status', 'pending')->count(),
            'validatedToday' => $validatedTodayQuery->count(),
            'newToday' => $newTodayQuery->count(),
            'weekTotal' => $weekTotalQuery->count(),
        ];

        return view('admin.pesanan-website.index', compact('pesanans', 'kpis', 'search', 'status', 'date'));
    }

    public function validate_pesanan(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:booked,batal',
        ]);

        $pesanan = PesananWebsite::findOrFail($id);
        $action = $request->action;

        // Update booking status if linked
        if ($pesanan->booking_id) {
            $booking = Booking::find($pesanan->booking_id);
            if ($booking) {
                if ($action === 'booked') {
                    $booking->update([
                        'job_status' => 'Booked',
                        'booking_status' => 'booked',
                    ]);
                } else {
                    $booking->update([
                        'job_status' => 'Batal',
                        'booking_status' => 'cancelled',
                    ]);
                }

                // Send notification to customer
                if ($booking->customer && $booking->customer->no_wa) {
                    $subject = $action === 'booked'
                        ? 'Booking Anda Disetujui'
                        : 'Booking Anda Ditolak';
                    $message = $action === 'booked'
                        ? "Booking #{$booking->booking_id} telah disetujui. Tim kami akan menghubungi Anda untuk persiapan acara."
                        : "Booking #{$booking->booking_id} saat ini tidak dapat diproses. Silakan hubungi admin untuk detail lebih lanjut.";

                    Notification::createNotification([
                        'booking_id' => $booking->booking_id,
                        'recipient_role' => 'CUSTOMER',
                        'phone_number' => $booking->customer->no_wa,
                        'notification_type' => $action === 'booked' ? 'booking_approved' : 'booking_rejected',
                        'subject' => $subject,
                        'message' => $message,
                        'channel' => 'whatsapp',
                    ]);
                }
            }
        }

        // Mark pesanan as done (remove from list)
        $pesanan->update(['status' => 'done']);

        $statusLabel = $action === 'booked' ? 'Booked (Disetujui)' : 'Batal (Ditolak)';
        return back()->with('success', "Pesanan berhasil divalidasi: {$statusLabel}");
    }
}
