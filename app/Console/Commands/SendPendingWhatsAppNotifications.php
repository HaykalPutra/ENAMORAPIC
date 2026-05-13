<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendPendingWhatsAppNotifications extends Command
{
    protected $signature = 'notifications:send-whatsapp {--limit=50}';

    protected $description = 'Send pending WhatsApp notifications from notifications table';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $whatsAppService = new WhatsAppService();

        $notifications = Notification::query()
            ->where('channel', 'whatsapp')
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($notifications->isEmpty()) {
            $this->info('No pending WhatsApp notifications.');
            return self::SUCCESS;
        }

        $sent = 0;
        $failed = 0;

        foreach ($notifications as $notification) {
            $phone = (string) ($notification->phone_number ?? '');
            if ($phone === '') {
                $notification->markAsFailed('phone_number kosong');
                $failed++;
                continue;
            }

            $ok = $whatsAppService->send($phone, $notification->message);

            if ($ok) {
                $notification->markAsSent();
                $sent++;
            } else {
                $notification->markAsFailed('provider send failed');
                $failed++;
            }
        }

        $this->info("Done. Sent: {$sent}, Failed: {$failed}");

        return self::SUCCESS;
    }
}
