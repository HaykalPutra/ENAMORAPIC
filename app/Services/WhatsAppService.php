<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $provider;

    public function __construct()
    {
        $this->provider = strtolower((string) config('services.whatsapp.provider', 'mock'));
    }

    /**
     * Send WhatsApp via selected provider.
     * Supported providers: mock, fonnte
     */
    public function send(string $phoneNumber, string $message): bool
    {
        $normalizedPhone = $this->normalizePhone($phoneNumber);
        if ($normalizedPhone === '') {
            return false;
        }

        if ($this->provider === 'fonnte') {
            return $this->sendViaFonnte($normalizedPhone, $message);
        }

        return $this->sendViaMock($normalizedPhone, $message);
    }

    private function sendViaMock(string $phoneNumber, string $message): bool
    {

        Log::info('WhatsApp queued', [
            'to' => $phoneNumber,
            'message' => $message,
            'provider' => 'mock',
        ]);

        return true;
    }

    private function sendViaFonnte(string $phoneNumber, string $message): bool
    {
        $token = (string) config('services.whatsapp.fonnte_token');
        $endpoint = (string) config('services.whatsapp.fonnte_endpoint', 'https://api.fonnte.com/send');

        if ($token === '') {
            Log::warning('Fonnte token is empty, fallback to mock');
            return $this->sendViaMock($phoneNumber, $message);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post($endpoint, [
                'target' => $phoneNumber,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp sent via fonnte', [
                    'to' => $phoneNumber,
                    'status' => $response->status(),
                ]);
                return true;
            }

            Log::error('WhatsApp send failed via fonnte', [
                'to' => $phoneNumber,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('WhatsApp send exception via fonnte', [
                'to' => $phoneNumber,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone) ?? '';
        if ($phone === '') {
            return '';
        }

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '62')) {
            return $phone;
        }

        return $phone;
    }
}
