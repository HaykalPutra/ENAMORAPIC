<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * QRIS Service - Generate QRIS Code untuk pembayaran
 * 
 * QRIS (Quick Response Code Indonesian Standard) adalah standar QR code
 * untuk transaksi pembayaran di Indonesia.
 * 
 * CARA PAKAI:
 * Letakkan file QRIS asli di folder public/assets/qris/ dengan format nama:
 *   qris_{jumlah}.png  (contoh: qris_2500000.png, qris_900000.png)
 * Sistem akan otomatis mencocokkan QRIS berdasarkan jumlah termin.
 */
class QRISService
{
    /**
     * Generate/ambil QRIS code image URL berdasarkan amount
     */
    public function generateQRISCode(array $data): string
    {
        $amount = (int) $data['amount'];

        // 1. Cari QRIS asli berdasarkan amount di public/assets/qris/
        $realQris = $this->findRealQRIS($amount);
        if ($realQris) {
            return $realQris;
        }

        // 2. Fallback: generate QR code otomatis
        return $this->generateAutoQRIS($data);
    }

    /**
     * Cari file QRIS asli dari folder public/assets/qris/ berdasarkan amount
     * Format file: qris_{amount}.png atau qris_{amount}.jpg
     */
    private function findRealQRIS(int $amount): ?string
    {
        $basePath = public_path('assets/qris');

        foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
            $filename = "qris_{$amount}.{$ext}";
            if (file_exists($basePath . '/' . $filename)) {
                return asset("assets/qris/{$filename}");
            }
        }

        return null;
    }

    /**
     * Generate QR code otomatis (fallback jika tidak ada QRIS asli)
     */
    private function generateAutoQRIS(array $data): string
    {
        $bookingId = $data['booking_id'];
        $termNumber = $data['term_number'];
        $amount = $data['amount'];
        $customerPhone = $data['customer_phone'] ?? null;

        $qrisString = $this->buildQRISString([
            'booking_id' => $bookingId,
            'term_number' => $termNumber,
            'amount' => $amount,
            'timestamp' => now(),
            'customer_phone' => $customerPhone,
        ]);

        try {
            $filename = 'qris_' . $bookingId . '_t' . $termNumber . '_' . time() . '.png';
            $path = storage_path('app/public/qris/' . $filename);

            if (!is_dir(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            QrCode::format('png')
                ->size(500)
                ->generate($qrisString, $path);

            return asset('storage/qris/' . $filename);

        } catch (\Exception $e) {
            return $this->generatePlaceholderQRISUrl($amount);
        }
    }

    /**
     * Build QRIS string dengan format standar
     */
    private function buildQRISString(array $data): string
    {
        $bookingId = $data['booking_id'];
        $termNumber = $data['term_number'];
        $amount = $data['amount'];
        $phone = $data['customer_phone'] ?? null;

        // Format QRIS info
        return json_encode([
            'type' => 'ENAMORA_BOOKING_PAYMENT',
            'booking_id' => $bookingId,
            'term_number' => $termNumber,
            'amount' => $amount,
            'currency' => 'IDR',
            'merchant' => 'ENAMORA PHOTOGRAPHY',
            'phone' => $phone,
            'timestamp' => $data['timestamp']->toIso8601String(),
            'description' => "Payment for Booking #{$bookingId}, Term {$termNumber}/{$amount}",
        ]);
    }

    /**
     * Generate placeholder QRIS URL (fallback)
     */
    private function generatePlaceholderQRISUrl(int $amount): string
    {
        $data = urlencode(json_encode([
            'merchant' => 'ENAMORA PHOTOGRAPHY',
            'amount' => $amount,
            'currency' => 'IDR',
        ]));

        return "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$data}";
    }

    /**
     * Generate QRIS text/message untuk WhatsApp
     */
    public function generateQRISMessage(array $paymentData): string
    {
        $bookingId = $paymentData['booking_id'];
        $termNumber = $paymentData['term_number'];
        $amount = $paymentData['amount'];
        $customerName = $paymentData['customer_name'] ?? 'Customer';
        $amountFormatted = 'Rp ' . number_format($amount, 0, ',', '.');

        return "Halo {$customerName},\n\n" .
               "📌 *Tagihan Pembayaran Termin {$termNumber}*\n" .
               "Booking ID: #{$bookingId}\n" .
               "Jumlah: {$amountFormatted}\n\n" .
               "Silakan scan QR Code di bawah atau transfer ke rekening kami.\n" .
               "Untuk informasi lebih lanjut, hubungi tim kami.\n\n" .
               "Terima kasih! 🙏\n" .
               "- Enamora Photography";
    }
}
