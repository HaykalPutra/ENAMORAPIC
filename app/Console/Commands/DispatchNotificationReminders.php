<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\PaymentTerm;
use App\Services\BookingStatusService;
use Illuminate\Console\Command;

class DispatchNotificationReminders extends Command
{
    public function __construct(private BookingStatusService $bookingStatusService)
    {
        parent::__construct();
    }

    protected $signature = 'notifications:dispatch-reminders';

    protected $description = 'Dispatch payment reminders, overdue alerts, and upcoming job notifications';

    public function handle(): int
    {
        $now = now();

        // 1) Reminder H-3 due date
        $dueSoonTerms = PaymentTerm::query()
            ->whereIn('payment_status', ['unpaid', 'overdue'])
            ->whereDate('due_date', $now->copy()->addDays(3)->toDateString())
            ->with('booking.customer')
            ->get();

        foreach ($dueSoonTerms as $term) {
            $booking = $term->booking;
            if (!$booking) {
                continue;
            }

            Notification::createNotification([
                'booking_id' => $booking->booking_id,
                'recipient_role' => 'CUSTOMER',
                'phone_number' => optional($booking->customer)->no_wa,
                'notification_type' => 'payment_due',
                'subject' => 'Reminder Jatuh Tempo Pembayaran',
                'message' => "Termin {$term->term_number} untuk booking #{$booking->booking_id} jatuh tempo pada {$term->due_date->format('d M Y')}.",
                'channel' => 'whatsapp',
            ]);
        }

        // 1b) Reminder H-1 untuk customer DP (besok jatuh tempo)
        $dueTomorrowTerms = PaymentTerm::query()
            ->whereIn('payment_status', ['unpaid', 'overdue'])
            ->whereDate('due_date', $now->copy()->addDay()->toDateString())
            ->where(function ($q) use ($now) {
                $q->whereNull('next_term_reminder_sent_at')
                    ->orWhereDate('next_term_reminder_sent_at', '<', $now->toDateString());
            })
            ->whereHas('booking', function ($q) {
                $q->where('status_pembayaran', 'DP');
            })
            ->with('booking.customer')
            ->get();

        foreach ($dueTomorrowTerms as $term) {
            $booking = $term->booking;
            if (!$booking) {
                continue;
            }

            Notification::createNotification([
                'booking_id' => $booking->booking_id,
                'recipient_role' => 'ADMIN',
                'notification_type' => 'payment_reminder',
                'subject' => 'Reminder DP H-1',
                'message' => "Booking #{$booking->booking_id} termin {$term->term_number} jatuh tempo besok ({$term->due_date->format('d M Y')}).",
                'channel' => 'dashboard',
            ]);

            $phone = (string) (optional($booking->customer)->no_wa ?? '');
            if ($phone !== '') {
                Notification::createNotification([
                    'booking_id' => $booking->booking_id,
                    'recipient_role' => 'CUSTOMER',
                    'phone_number' => $phone,
                    'notification_type' => 'payment_reminder',
                    'subject' => 'Reminder Pembayaran H-1',
                    'message' => "Reminder: Termin {$term->term_number} untuk booking #{$booking->booking_id} jatuh tempo besok ({$term->due_date->format('d M Y')}).",
                    'channel' => 'whatsapp',
                ]);
            }

            $term->update([
                'next_term_reminder_sent_at' => now(),
            ]);
        }

        // 2) Overdue alert for admin
        $overdueTerms = PaymentTerm::query()
            ->where('payment_status', 'overdue')
            ->with('booking')
            ->get();

        foreach ($overdueTerms as $term) {
            if (!$term->booking) {
                continue;
            }

            Notification::createNotification([
                'booking_id' => $term->booking->booking_id,
                'recipient_role' => 'ADMIN',
                'notification_type' => 'late_payment_alert',
                'subject' => 'Pembayaran Terlambat',
                'message' => "Booking #{$term->booking->booking_id} termin {$term->term_number} melewati due date.",
                'channel' => 'dashboard',
            ]);
        }

        // 3) Upcoming job alert H-7 and H-1
        $upcomingBookings = Booking::query()
            ->whereIn('booking_status', ['booked', 'pending'])
            ->whereIn('tgl_acara', [
                $now->copy()->addDays(7)->toDateString(),
                $now->copy()->addDay()->toDateString(),
            ])
            ->get();

        foreach ($upcomingBookings as $booking) {
            $daysLeft = $now->diffInDays($booking->tgl_acara, false);

            Notification::createNotification([
                'booking_id' => $booking->booking_id,
                'recipient_role' => 'ADMIN',
                'notification_type' => 'job_upcoming',
                'subject' => 'Job Upcoming Alert',
                'message' => "Booking #{$booking->booking_id} akan berlangsung {$daysLeft} hari lagi.",
                'channel' => 'dashboard',
            ]);
        }

        // 4) Lead follow-up reminder (CRM-lite)
        $leadDueToday = Lead::query()
            ->whereDate('next_follow_up_at', $now->toDateString())
            ->whereIn('status', ['prospect', 'negotiation'])
            ->where(function ($q) {
                $q->whereNull('last_follow_up_sent_at')
                    ->orWhereDate('last_follow_up_sent_at', '<', now()->toDateString());
            })
            ->get();

        foreach ($leadDueToday as $lead) {
            Notification::createNotification([
                'recipient_role' => 'ADMIN',
                'notification_type' => 'follow_up_needed',
                'subject' => 'Follow-up Lead Hari Ini',
                'message' => "Follow-up lead {$lead->nama} ({$lead->no_wa}) hari ini.",
                'channel' => 'dashboard',
                'metadata' => ['lead_id' => $lead->lead_id],
            ]);

            $leadPhone = preg_replace('/\D+/', '', (string) $lead->no_wa) ?? '';
            if ($leadPhone !== '') {
                Notification::createNotification([
                    'recipient_role' => 'CUSTOMER',
                    'phone_number' => $lead->no_wa,
                    'notification_type' => 'follow_up_needed',
                    'subject' => 'Follow-up Konsep Booking',
                    'message' => "Halo {$lead->nama}, ini reminder untuk follow-up konsep/bonus paket. Jika ada kebutuhan tambahan, kabari kami ya.",
                    'channel' => 'whatsapp',
                    'metadata' => ['lead_id' => $lead->lead_id],
                ]);
            }

            $lead->update([
                'last_follow_up_sent_at' => now(),
            ]);
        }

        // 5) Auto-complete for past booked jobs
        $this->bookingStatusService->syncPastBookedToCompleted();

        $this->info('Notification reminders dispatched.');

        return self::SUCCESS;
    }
}
