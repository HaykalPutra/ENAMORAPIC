<?php

namespace App\Services;

use App\Models\PaymentLog;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentGatewayService
{
    private string $gatewayName = 'midtrans';

    public function __construct()
    {
        Config::$serverKey = (string) config('services.midtrans.server_key');
        Config::$clientKey = (string) config('services.midtrans.client_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Initiate payment
     */
    public function initiatePayment(
        int $bookingId,
        int $termNumber,
        int $amount,
        string $paymentMethod = 'transfer',
        ?string $customerEmail = null,
        ?string $customerPhone = null,
        ?string $customerName = null
    ): array {
        try {
            $orderId = $this->generateOrderId($bookingId, $termNumber);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $amount,
                ],
                'item_details' => [[
                    'id' => "booking-{$bookingId}-term-{$termNumber}",
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => "Booking #{$bookingId} Termin {$termNumber}",
                ]],
                'customer_details' => [
                    'first_name' => $customerName ?: "Customer {$bookingId}",
                    'email' => $customerEmail ?: "booking-{$bookingId}@enamorapic.local",
                    'phone' => $this->normalizePhone($customerPhone),
                ],
            ];

            $enabledPayments = $this->mapEnabledPayments($paymentMethod);
            if (!empty($enabledPayments)) {
                $params['enabled_payments'] = $enabledPayments;
            }

            $snapToken = Snap::getSnapToken($params);

            // Log payment attempt
            $paymentLog = PaymentLog::create([
                'booking_id' => $bookingId,
                'term_number' => $termNumber,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'transaction_id' => $orderId,
                'gateway_name' => $this->gatewayName,
                'gateway_response' => [
                    'status' => 'pending',
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'currency' => 'IDR',
                    'snap_token' => $snapToken,
                    'snap_redirect_url' => "https://app.midtrans.com/snap/v2/vtweb/{$snapToken}",
                    'created_at' => now(),
                ],
            ]);

            return [
                'success' => true,
                'message' => 'Midtrans transaction created',
                'transaction_id' => $orderId,
                'payment_log_id' => $paymentLog->id,
                'snap_token' => $snapToken,
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'status' => 'pending',
                'checkout_url' => $this->getCheckoutUrl($orderId),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Payment initiation failed: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment dari gateway (callback/webhook)
     */
    public function verifyPayment(string $transactionId): array
    {
        try {
            $paymentLog = PaymentLog::where('transaction_id', $transactionId)->first();

            if (!$paymentLog) {
                return [
                    'success' => false,
                    'message' => 'Transaction not found',
                ];
            }

            $midtransStatus = Transaction::status($transactionId);
            $paymentStatus = $this->mapMidtransStatus((array) $midtransStatus);

            $paymentLog->update([
                'payment_status' => $paymentStatus['db_status'],
                'gateway_response' => array_merge(
                    (array) ($paymentLog->gateway_response ?? []),
                    (array) $midtransStatus
                ),
                'error_message' => $paymentStatus['db_status'] === 'failed' ? $paymentStatus['message'] : null,
            ]);

            if ($paymentStatus['paid'] && !$paymentLog->isSuccess()) {
                $paymentLog->markAsSuccess($transactionId);
            }

            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'status' => $paymentStatus['status'],
                'db_status' => $paymentStatus['db_status'],
                'paid' => $paymentStatus['paid'],
                'message' => $paymentStatus['message'],
                'raw' => (array) $midtransStatus,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage(),
            ];
        }
    }

    public function handleNotification(array $payload): array
    {
        $orderId = $payload['order_id'] ?? null;
        if (!$orderId) {
            return [
                'success' => false,
                'message' => 'order_id not found in payload',
            ];
        }

        $paymentLog = PaymentLog::where('transaction_id', $orderId)->first();

        if (!$paymentLog) {
            return [
                'success' => false,
                'message' => 'Transaction not found',
            ];
        }

        $mapped = $this->mapMidtransStatus($payload);

        $paymentLog->update([
            'payment_status' => $mapped['db_status'],
            'gateway_response' => array_merge((array) ($paymentLog->gateway_response ?? []), $payload),
            'error_message' => $mapped['db_status'] === 'failed' ? $mapped['message'] : null,
        ]);

        return [
            'success' => true,
            'transaction_id' => $orderId,
            'status' => $mapped['status'],
            'db_status' => $mapped['db_status'],
            'paid' => $mapped['paid'],
            'message' => $mapped['message'],
            'payment_log' => $paymentLog,
        ];
    }

    /**
     * Validate Midtrans notification signature
     */
    public function isValidSignature(array $payload): bool
    {
        $serverKey = (string) config('services.midtrans.server_key');
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature = (string) ($payload['signature_key'] ?? '');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signature === '') {
            return false;
        }

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($expected, $signature);
    }

    /**
     * Get checkout URL untuk payment gateway
     */
    private function getCheckoutUrl(string $transactionId): string
    {
        return route('payment.checkout', ['transaction_id' => $transactionId]);
    }

    /**
     * Refund payment
     */
    public function refundPayment(string $transactionId, ?int $amount = null): array
    {
        try {
            $paymentLog = PaymentLog::where('transaction_id', $transactionId)->first();

            if (!$paymentLog || $paymentLog->payment_status !== 'success') {
                return [
                    'success' => false,
                    'message' => 'Cannot refund: Payment not found or not successful',
                ];
            }

            $refundAmount = $amount ?? $paymentLog->amount;

            // Simulate refund
            $paymentLog->update([
                'payment_status' => 'refunded',
                'gateway_response' => json_encode([
                    'original_amount' => $paymentLog->amount,
                    'refund_amount' => $refundAmount,
                    'refunded_at' => now(),
                ]),
            ]);

            return [
                'success' => true,
                'message' => 'Payment refunded successfully',
                'refund_amount' => $refundAmount,
                'transaction_id' => $transactionId,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Refund failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Sandbox: Manually mark payment as success (for testing)
     */
    public function sandboxMarkAsSuccess(string $transactionId): array
    {
        if ((bool) config('services.midtrans.is_production', false)) {
            return [
                'success' => false,
                'message' => 'This feature only works in sandbox mode',
            ];
        }

        $paymentLog = PaymentLog::where('transaction_id', $transactionId)->first();

        if (!$paymentLog) {
            return [
                'success' => false,
                'message' => 'Transaction not found',
            ];
        }

        $paymentLog->markAsSuccess($transactionId);

        return [
            'success' => true,
            'message' => 'Payment marked as success in sandbox',
            'transaction_id' => $transactionId,
        ];
    }

    private function generateOrderId(int $bookingId, int $termNumber): string
    {
        return sprintf('ENAMORA-%d-T%d-%d', $bookingId, $termNumber, time());
    }

    private function mapEnabledPayments(string $paymentMethod): array
    {
        return match($paymentMethod) {
            'qris' => ['gopay', 'shopeepay', 'qris'],
            'transfer' => ['bank_transfer'],
            default => [],
        };
    }

    private function normalizePhone(?string $phone): string
    {
        if (!$phone) {
            return '';
        }

        $digits = preg_replace('/\D+/', '', $phone);
        if (!$digits) {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (!str_starts_with($digits, '62')) {
            return '62' . $digits;
        }

        return $digits;
    }

    private function mapMidtransStatus(array $data): array
    {
        $transactionStatus = (string) ($data['transaction_status'] ?? 'pending');
        $fraudStatus = (string) ($data['fraud_status'] ?? 'accept');

        if (in_array($transactionStatus, ['settlement', 'capture'], true) && $fraudStatus !== 'challenge') {
            return [
                'status' => $transactionStatus,
                'db_status' => 'success',
                'paid' => true,
                'message' => 'Payment successful',
            ];
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true)) {
            return [
                'status' => $transactionStatus,
                'db_status' => 'failed',
                'paid' => false,
                'message' => 'Payment failed',
            ];
        }

        if ($transactionStatus === 'pending') {
            return [
                'status' => 'pending',
                'db_status' => 'pending',
                'paid' => false,
                'message' => 'Payment pending',
            ];
        }

        return [
            'status' => $transactionStatus,
            'db_status' => 'processing',
            'paid' => false,
            'message' => 'Payment is processing',
        ];
    }
}
