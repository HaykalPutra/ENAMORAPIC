<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\Paket;
use App\Models\PesananWebsite;
use App\Models\User;
use App\Services\PaymentTermService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $paket = Paket::findOrFail($request->paket_id);

        // Validate berdasarkan tipe paket
        $this->validateBookingRequest($request, $paket);

        DB::beginTransaction();
        try {
            // 1. Cek/buat Customer 
            $normalizedWa = $this->normalizeWhatsApp($request->wa);
            $normalizedName = trim((string) $request->nama);

            $customer = Customer::firstOrCreate(
                [
                    'nama_client' => $normalizedName,
                    'no_wa' => $normalizedWa,
                ],
                ['alamat' => 0]
            );

            // 2. Hitung total termin based on paket type
            $totalTermin = $paket->getTerminCount(); 

            // 3. Simpan Booking utama
            $booking = Booking::create([
                'customer_id'       => $customer->customer_id,
                'nama_client'       => $request->nama,
                'tgl_booking'       => now()->toDateString(),
                'tgl_acara'         => $request->tgl_acara,
                'tgl_prewedd'       => $request->tgl_prewedd ?? null,
                'lokasi_wedding'    => $request->lokasi_wedding,
                'lokasi_prewedd'    => $request->lokasi_prewedd ?? null,
                'total_transaksi'   => $paket->harga,
                'status_pembayaran' => $request->status_bayar,
                'job_status'        => 'Pending',
                'total_termin'      => $totalTermin,
                'booking_status'    => 'pending', 
            ]);

            // 4. Simpan booking_detail
            BookingDetail::create([
                'booking_id'     => $booking->booking_id,
                'paket_id'       => $paket->paket_id,
                'harga_saat_ini' => $paket->harga,
            ]);

            // 5. Generate payment terms schedule
            PaymentTermService::createPaymentTerms($booking);

            // 6. Simpan ke pesanan_website juga
            $pesanan = PesananWebsite::create([
                'booking_id'      => $booking->booking_id,
                'paket_id'        => $paket->paket_id,
                'nama_pemesan'    => $normalizedName,
                'no_wa'           => $normalizedWa,
                'tanggal_booking' => $request->tgl_acara,
                'lokasi'          => $request->lokasi_wedding,
                'status'          => 'pending',
            ]);

            // 7. Notif Dashboard CEO
            $ceoUsers = User::where('role', 'CEO')->orWhere('role', 'ADMIN')->get();
            foreach ($ceoUsers as $ceoUser) {
                Notification::createNotification([
                    'booking_id' => $booking->booking_id,
                    'user_id' => $ceoUser->user_id,
                    'recipient_role' => $ceoUser->role,
                    'notification_type' => 'new_booking',
                    'subject' => "Booking Baru dari {$request->nama}",
                    'message' => "Booking baru untuk paket {$paket->nama_paket} pada tanggal {$request->tgl_acara}. Menunggu persetujuan Anda.",
                    'channel' => 'dashboard',
                ]);
            }

            // 8. Notif Dashboard Staf
            $staffUsers = User::where('role', 'ADMIN')->get();
            foreach ($staffUsers as $staffUser) {
                Notification::createNotification([
                    'booking_id' => $booking->booking_id,
                    'user_id' => $staffUser->user_id,
                    'recipient_role' => $staffUser->role,
                    'notification_type' => 'follow_up_needed',
                    'subject' => "Follow-up Booking {$request->nama}",
                    'message' => "Ada booking baru dari {$request->nama}. Siapkan follow-up ke customer untuk konfirmasi pembayaran.",
                    'channel' => 'dashboard',
                ]);
            }

            // ====================================================================
            // 9. WA API: TEMPLATE PESAN PREMIUM
            // ====================================================================

            $waSekretaris = config('services.whatsapp.admin_phone', '6281320689145'); 
            $waOwner = "628980564584"; 

            $hargaFormat = number_format((float) $paket->harga, 0, ',', '.');
            
            // TEMPLATE UNTUK ADMIN/OWNER
            $pesanNotifTim = "🚨 *NEW BOOKING ALERT!* 🚨\n"
                           . "───────────────────────────\n"
                           . "Halo Tim Enamorapic, ada pesanan baru yang masuk melalui website!\n\n"
                           . "👤 *Informasi Klien:*\n"
                           . "• Nama : {$request->nama}\n"
                           . "• WA : {$normalizedWa}\n\n"
                           . "📋 *Detail Pesanan:*\n"
                           . "• ID Booking : *#{$booking->booking_id}*\n"
                           . "• Paket : {$paket->nama_paket}\n"
                           . "• Tgl Acara : {$request->tgl_acara}\n"
                           . "• Lokasi : {$request->lokasi_wedding}\n"
                           . "• Total Harga: Rp {$hargaFormat}\n\n"
                           . "Silakan segera cek ketersediaan jadwal di Dashboard Admin dan *Follow Up* klien ini. 💻✨";

            // TEMPLATE UNTUK CUSTOMER
            $pesanNotifCustomer = "✨ *HELLO FROM ENAMORAPIC!* ✨\n"
                           . "───────────────────────────\n"
                           . "Hai *{$request->nama}*, terima kasih telah memilih Enamorapic untuk mengabadikan momen spesial Anda! 📸🤍\n\n"
                           . "Berikut adalah detail pesanan Anda yang telah kami terima:\n"
                           . "🏷️ *ID Booking :* #{$booking->booking_id}\n"
                           . "📦 *Paket :* {$paket->nama_paket}\n"
                           . "📅 *Tgl Acara :* {$request->tgl_acara}\n"
                           . "📍 *Lokasi :* {$request->lokasi_wedding}\n\n"
                           . "Tim kami sedang memvalidasi jadwal Anda. Silakan lanjutkan ke tahap pembayaran pada website agar pesanan dapat segera kami kunci *(lock tanggal)*.\n\n"
                           . "Jika ada pertanyaan, jangan ragu untuk membalas pesan ini.\n"
                           . "Have a wonderful day! 🕊️";

            try {
                // Tembak API ke Sekretaris
                Http::post('http://localhost:3000/send-message', [
                    'number' => $waSekretaris,
                    'message' => $pesanNotifTim,
                    'password' => '11223344'
                ]);

                // Tembak API ke Owner
                Http::post('http://localhost:3000/send-message', [
                    'number' => $waOwner,
                    'message' => $pesanNotifTim,
                    'password' => '11223344'
                ]);

                // Tembak API ke Customer
                Http::post('http://localhost:3000/send-message', [
                    'number' => $normalizedWa,
                    'message' => $pesanNotifCustomer,
                    'password' => '11223344'
                ]);
            } catch (\Exception $e) {
                \Log::error('API WA Gagal Eksekusi: ' . $e->getMessage());
            }
            // ====================================================================

            DB::commit();

            return redirect()->route('payment.show', $booking->booking_id)
                ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function validateBookingRequest(Request $request, Paket $paket)
    {
        $rules = [
            'paket_id'     => 'required|exists:paket,paket_id',
            'nama'         => 'required|string|max:100',
            'wa'           => 'required|string|max:20',
            'tgl_acara'    => 'required|date|after_or_equal:today',
            'status_bayar' => 'required|in:DP,Lunas',
        ];

        if ($paket->isAllIn()) {
            $rules['tgl_prewedd'] = 'required|date|before:' . $request->tgl_acara;
            $rules['lokasi_prewedd'] = 'required|string|max:255';
            $rules['lokasi_wedding'] = 'required|string|max:255';
        } else {
            $rules['lokasi_wedding'] = 'required|string|max:255';
        }

        return $request->validate($rules);
    }

    private function normalizeWhatsApp(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if ($digits === '') return '-';
        if (str_starts_with($digits, '0')) return '62' . substr($digits, 1);
        if (!str_starts_with($digits, '62')) return '62' . $digits;

        return $digits;
    }
}