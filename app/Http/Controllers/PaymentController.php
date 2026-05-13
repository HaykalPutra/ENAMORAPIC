<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\PaymentLog;
use App\Models\PaymentTerm;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // <-- UNTUK API WA

class PaymentController extends Controller
{
    protected PaymentGatewayService $gateway;

    public function __construct()
    {
        $this->gateway = new PaymentGatewayService();
    }

    public function show(Booking $booking)
    {
        $booking->load(['details.paket', 'customer', 'paymentTerms']);

        $unpaidTerms = $booking->paymentTerms()
            ->whereIn('payment_status', ['unpaid', 'overdue'])
            ->orderBy('term_number')
            ->get();

        $allTerms = $booking->paymentTerms()->orderBy('term_number')->get();

        if ($unpaidTerms->isEmpty() && $allTerms->isNotEmpty()) {
            $paketName = optional($booking->details->first())->paket->nama_paket ?? '-';
            $paidTerms = $allTerms->filter(fn($t) => $t->isPaid());
            $totalPaid = $paidTerms->sum('term_amount');
            $adminPhone = config('services.whatsapp.admin_phone', '628980564584');
            $waMessage = $this->buildWhatsAppConfirmation($booking, $paketName, $paidTerms, $totalPaid);

            return view('customer.payments.done', [
                'booking' => $booking,
                'adminPhone' => $adminPhone,
                'waMessage' => $waMessage,
            ]);
        }

        $currentTerm = $unpaidTerms->first();
        $hasPaidTerm = $allTerms->contains(fn($t) => $t->isPaid());
        $adminPhone = config('services.whatsapp.admin_phone', '628980564584');

        $waMessage = null;
        if ($hasPaidTerm) {
            $paketName = optional($booking->details->first())->paket->nama_paket ?? '-';
            $paidTerms = $allTerms->filter(fn($t) => $t->isPaid());
            $totalPaid = $paidTerms->sum('term_amount');

            $waMessage = $this->buildWhatsAppConfirmation($booking, $paketName, $paidTerms, $totalPaid);
        }

        return view('customer.payments.index', [
            'booking' => $booking,
            'allTerms' => $allTerms,
            'unpaidTerms' => $unpaidTerms,
            'currentTerm' => $currentTerm,
            'paymentSummary' => $this->getPaymentSummary($booking),
            'hasPaidTerm' => $hasPaidTerm,
            'adminPhone' => $adminPhone,
            'waMessage' => $waMessage,
        ]);
    }

    public function initiate(Request $request, Booking $booking)
    {
        $request->validate([
            'term_id' => 'required|exists:payment_terms,id',
            'payment_method' => 'required|in:transfer,qris',
        ]);

        $term = PaymentTerm::findOrFail($request->term_id);

        if ($term->booking_id !== $booking->booking_id) {
            return redirect()->back()->with('error', 'Invalid term');
        }

        if ($term->isPaid()) {
            return redirect()->back()->with('error', 'Termin ini sudah dibayar');
        }

        DB::beginTransaction();
        try {
            $paymentResult = $this->gateway->initiatePayment(
                $booking->booking_id,
                $term->term_number,
                (int) $term->term_amount,
                $request->payment_method,
                null,
                optional($booking->customer)->no_wa,
                $booking->nama_client,
            );

            if (!$paymentResult['success']) {
                DB::rollBack();
                return redirect()->back()->with('error', $paymentResult['message']);
            }

            DB::commit();

            return redirect()->route('payment.checkout', [
                'transaction_id' => $paymentResult['transaction_id'],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function checkout(Request $request)
    {
        $transactionId = $request->query('transaction_id');
        $paymentLog = PaymentLog::where('transaction_id', $transactionId)->first();

        if (!$paymentLog) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan');
        }

        $booking = Booking::with(['details.paket', 'customer'])->find($paymentLog->booking_id);
        $term = $booking->paymentTerms()
            ->where('term_number', $paymentLog->term_number)
            ->first();

        $gatewayResponse = (array) ($paymentLog->gateway_response ?? []);
        $snapToken = $gatewayResponse['snap_token'] ?? null;

        if (!$snapToken) {
            return redirect()->route('payment.show', $booking->booking_id)
                ->with('error', 'Token checkout Midtrans tidak ditemukan. Silakan ulangi pembayaran.');
        }

        return view('customer.payments.checkout', [
            'booking' => $booking,
            'paymentLog' => $paymentLog,
            'term' => $term,
            'snapToken' => $snapToken,
            'midtransClientKey' => config('services.midtrans.client_key'),
            'isMidtransProduction' => (bool) config('services.midtrans.is_production', false),
            'amountFormatted' => 'Rp ' . number_format((float) $paymentLog->amount, 0, ',', '.'),
        ]);
    }

    public function verify(Request $request, Booking $booking)
    {
        $transactionId = $request->query('transaction_id');
        $paymentLog = PaymentLog::where('transaction_id', $transactionId)
            ->where('booking_id', $booking->booking_id)
            ->first();

        if (!$paymentLog) {
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }

        $verifyResult = $this->gateway->verifyPayment($transactionId);

        if (!$verifyResult['success']) {
            return response()->json([
                'success' => false,
                'message' => $verifyResult['message'] ?? 'Verification failed',
            ], 400);
        }

        if ($verifyResult['paid']) {
            $this->applyPaymentSuccess($paymentLog);
            return response()->json(['success' => true, 'message' => 'Payment verified', 'status' => 'paid']);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment not yet confirmed',
            'status' => $verifyResult['db_status'] ?? $paymentLog->payment_status,
        ]);
    }

    public function callback(Request $request)
    {
        $data = $request->all();
        $transactionId = $data['order_id'] ?? null;

        if (!$transactionId) return response()->json(['success' => false], 400);
        if (!$this->gateway->isValidSignature($data)) return response()->json(['success' => false], 403);

        $result = $this->gateway->handleNotification($data);
        if (!$result['success']) return response()->json(['success' => false], 404);

        $paymentLog = PaymentLog::where('transaction_id', $transactionId)->first();
        if ($result['paid'] && $paymentLog) {
            $this->applyPaymentSuccess($paymentLog);
        }

        return response()->json(['success' => true, 'status' => $result['status'] ?? 'pending']);
    }

    public function testMarkSuccess(Request $request, Booking $booking)
    {
        $request->validate(['transaction_id' => 'required|string']);
        $result = $this->gateway->sandboxMarkAsSuccess($request->transaction_id);

        if ($result['success']) {
            $paymentLog = PaymentLog::where('transaction_id', $request->transaction_id)->first();
            if ($paymentLog) $this->applyPaymentSuccess($paymentLog);
            
            return redirect()->route('payment.show', $booking->booking_id)->with('success', 'Pembayaran berhasil! Terima kasih.');
        }
        return redirect()->back()->with('error', $result['message']);
    }

    private function applyPaymentSuccess(PaymentLog $paymentLog): void
    {
        $booking = $paymentLog->booking;
        if (!$booking) return;

        $term = $booking->paymentTerms()->where('term_number', $paymentLog->term_number)->first();
        if (!$term || $term->isPaid()) return;

        $term->markAsPaid($paymentLog->amount, $paymentLog->payment_method);
        $this->syncBookingPaymentStatus($booking);
        $this->createPaymentReceivedNotifications($booking, $term);
        $this->notifyAdminWhatsApp($booking, $term);
    }

    private function getPaymentSummary(Booking $booking): array
    {
        return [
            'total_amount' => $booking->total_transaksi,
            'total_paid' => $booking->getTotalPaid(),
            'total_remaining' => $booking->getTotalRemaining(),
            'is_fully_paid' => $booking->isFullyPaid(),
            'paid_percentage' => round(($booking->getTotalPaid() / $booking->total_transaksi) * 100, 1),
        ];
    }

    private function syncBookingPaymentStatus(Booking $booking): void
    {
        $booking->refresh();
        $booking->update([
            'status_pembayaran' => $booking->isFullyPaid() ? 'Lunas' : 'DP',
        ]);
    }

    /**
     * Notif Dashboard & WA untuk Customer
     */
    private function createPaymentReceivedNotifications(Booking $booking, PaymentTerm $term): void
    {
        $customerPhone = optional($booking->customer)->no_wa;
        $amountFmt = number_format((float) $term->term_amount, 0, ',', '.');
        
        $pesanCustomer = "💌 *PAYMENT RECEIVED* 💌\n"
                       . "───────────────────────────\n"
                       . "Hai *{$booking->nama_client}*, yay! 🎉\n\n"
                       . "Pembayaran untuk *Termin {$term->term_number}* pesanan Anda (ID: #{$booking->booking_id}) sebesar *Rp {$amountFmt}* telah berhasil diverifikasi oleh sistem. ✅\n\n"
                       . "Terima kasih telah melakukan pembayaran tepat waktu. Anda bisa mengecek detail lengkapnya melalui tautan pembayaran Anda di website.\n\n"
                       . "Let's create beautiful memories together! 📸✨";

        try {
            Http::post('http://localhost:3000/send-message', [
                'number' => $customerPhone,
                'message' => $pesanCustomer,
                'password' => '11223344'
            ]);
        } catch (\Exception $e) {
            \Log::error('API WA Error Payment Customer: ' . $e->getMessage());
        }

        Notification::createNotification([
            'booking_id' => $booking->booking_id,
            'recipient_role' => 'CEO',
            'notification_type' => 'payment_received',
            'subject' => 'Payment Masuk',
            'message' => "Booking #{$booking->booking_id} ({$booking->nama_client}) menerima pembayaran termin {$term->term_number} sebesar Rp {$amountFmt}.",
            'channel' => 'dashboard',
        ]);
    }

    /**
     * Kirim Notif ke Admin & Owner 
     */
    private function notifyAdminWhatsApp(Booking $booking, PaymentTerm $term): void
    {
        $adminPhone = config('services.whatsapp.admin_phone', '6281320689145'); 
        $waOwner = "628980564584"; 
        
        $paketName = optional($booking->details->first())->paket->nama_paket ?? 'Unknown';
        $amountFmt = number_format((float) $term->term_amount, 0, ',', '.');

        $pesanAdmin = "💸 *PAYMENT SUCCESS!* 💸\n"
                    . "───────────────────────────\n"
                    . "Yuhuu! Ada pembayaran masuk yang sudah diverifikasi oleh Payment Gateway. 🚀\n\n"
                    . "👤 *Klien:* {$booking->nama_client}\n"
                    . "📦 *Paket:* {$paketName}\n"
                    . "🏷️ *ID Booking:* #{$booking->booking_id}\n\n"
                    . "💳 *Detail Pembayaran:*\n"
                    . "• Termin : Ke-{$term->term_number}\n"
                    . "• Jumlah : Rp {$amountFmt}\n\n"
                    . "Yuk, segera update status pekerjaan dan persiapan tim di dashboard! 🔥";

        try {
            Http::post('http://localhost:3000/send-message', ['number' => $adminPhone, 'message' => $pesanAdmin, 'password' => '11223344']);
            Http::post('http://localhost:3000/send-message', ['number' => $waOwner, 'message' => $pesanAdmin, 'password' => '11223344']);
        } catch (\Exception $e) {
            \Log::error('API WA Error Payment Admin: ' . $e->getMessage());
        }
    }

    /**
     * Template Autotext WA Manual dari Customer
     */
    private function buildWhatsAppConfirmation(Booking $booking, string $paketName, $paidTerms, int $totalPaid): string
    {
        $tglAcara = \Carbon\Carbon::parse($booking->tgl_acara)->format('d / m / Y');
        $lokasiWedding = $booking->lokasi_wedding ?? '-';
        $totalFormatted = 'Rp' . number_format((float) $booking->total_transaksi, 0, ',', '.');

        $terminDetail = '';
        foreach ($paidTerms as $term) {
            $amountFmt = 'Rp' . number_format((float) $term->term_amount, 0, ',', '.');
            $paidDate = $term->paid_at ? \Carbon\Carbon::parse($term->paid_at)->format('d/m/Y') : 'Sudah';
            $terminDetail .= "• Termin {$term->term_number} : {$amountFmt} ✅\n";
        }

        $totalPaidFmt = 'Rp' . number_format((float) $totalPaid, 0, ',', '.');

        $message = "Halo Tim Enamorapic! ✨\n"
            . "Saya ingin melakukan konfirmasi pembayaran dengan detail berikut:\n\n"
            . "───────────────────────────\n"
            . "👤 *DATA KLIEN*\n"
            . "• Nama : {$booking->nama_client}\n"
            . "• Tgl Acara : {$tglAcara}\n"
            . "• Lokasi : {$lokasiWedding}\n\n"
            . "📦 *DETAIL PAKET*\n"
            . "• Paket : {$paketName}\n"
            . "• Total Harga : {$totalFormatted}\n\n"
            . "💳 *STATUS PEMBAYARAN*\n"
            . "{$terminDetail}"
            . "• Total Dibayar : {$totalPaidFmt}\n"
            . "───────────────────────────\n"
            . "🏷️ *Booking ID:* #{$booking->booking_id}\n\n"
            . "Mohon segera dicek dan dikonfirmasi ya kak. Terima kasih! 🙏📸";

        return $message;
    }
}