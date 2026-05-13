<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;

class BookingStatusService
{
    public function syncPastBookedToCompleted(): int
    {
        $updatedCount = 0;

        Booking::query()
            ->where('booking_status', 'booked')
            ->whereDate('tgl_acara', '<', now()->toDateString())
            ->chunkById(100, function ($bookings) use (&$updatedCount): void {
                foreach ($bookings as $booking) {
                    $booking->update([
                        'booking_status' => 'completed',
                        'job_status' => 'Selesai',
                    ]);

                    $updatedCount++;
                    $this->notifyAutoCompleted($booking);
                }
            }, 'booking_id');

        return $updatedCount;
    }

    private function notifyAutoCompleted(Booking $booking): void
    {
        $alreadyNotified = Notification::query()
            ->where('booking_id', $booking->booking_id)
            ->where('notification_type', 'general')
            ->where('subject', 'Booking Otomatis Selesai')
            ->exists();

        if ($alreadyNotified) {
            return;
        }

        $receivers = User::query()
            ->whereIn('role', ['CEO', 'ADMIN'])
            ->get();

        foreach ($receivers as $receiver) {
            Notification::createNotification([
                'booking_id' => $booking->booking_id,
                'user_id' => $receiver->user_id,
                'recipient_role' => $receiver->role,
                'notification_type' => 'general',
                'subject' => 'Booking Otomatis Selesai',
                'message' => "Booking #{$booking->booking_id} ({$booking->nama_client}) otomatis berubah menjadi Selesai karena tanggal acara sudah terlewati.",
                'channel' => 'dashboard',
            ]);
        }
    }
}
