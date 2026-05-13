<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\PesananWebsite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            $data = [
                'pendingWebsiteCount' => 0,
                'pendingBookingCount' => 0,
                'newBookingTodayCount' => 0,
                'topbarNotifItems' => collect(),
            ];

            if (!Auth::check()) {
                $view->with($data);
                return;
            }

            try {
                $pendingWebsiteCount = PesananWebsite::where('status', 'pending')->count();
                $pendingBookingCount = Booking::where('booking_status', 'pending')->count();
                $newBookingTodayCount = Booking::whereDate('tgl_booking', today())->count();

                $latestWebsiteOrders = PesananWebsite::where('status', 'pending')
                    ->orderByDesc('created_at')
                    ->limit(30)
                    ->get(['pesanan_id', 'nama_pemesan', 'created_at']);

                $latestPendingBookings = Booking::where('booking_status', 'pending')
                    ->orderByDesc('tgl_booking')
                    ->limit(30)
                    ->get(['booking_id', 'nama_client', 'tgl_booking']);

                $notifItems = collect();

                foreach ($latestWebsiteOrders as $order) {
                    $notifItems->push([
                        'title' => 'Pesanan website baru',
                        'subtitle' => $order->nama_pemesan,
                        'time' => optional($order->created_at)->diffForHumans() ?? '-',
                        'url' => route('admin.pesanan-website'),
                        'type' => 'website',
                    ]);
                }

                foreach ($latestPendingBookings as $booking) {
                    $notifItems->push([
                        'title' => 'Booking menunggu approval',
                        'subtitle' => $booking->nama_client,
                        'time' => optional($booking->tgl_booking)->format('d M Y') ?? '-',
                        'url' => route('admin.pesanan.show', $booking->booking_id),
                        'type' => 'booking',
                    ]);
                }

                $data = [
                    'pendingWebsiteCount' => $pendingWebsiteCount,
                    'pendingBookingCount' => $pendingBookingCount,
                    'newBookingTodayCount' => $newBookingTodayCount,
                    'topbarNotifItems' => $notifItems,
                ];
            } catch (\Throwable $e) {
                // Jangan ganggu rendering layout jika query notifikasi gagal.
            }

            $view->with($data);
        });

        View::composer('layouts.admin2', function ($view) {
            $data = [
                'admin2UpcomingWeddingCount' => 0,
                'admin2DpCount' => 0,
                'admin2ApprovedCount' => 0,
                'admin2NotifItems' => collect(),
            ];

            if (!Auth::check()) {
                $view->with($data);
                return;
            }

            try {
                $upcomingWeddings = Booking::query()
                    ->where('job_status', '!=', 'Batal')
                    ->whereDate('tgl_acara', '>=', today())
                    ->orderBy('tgl_acara')
                    ->limit(30)
                    ->get(['booking_id', 'nama_client', 'tgl_acara']);

                $dpBookings = Booking::query()
                    ->where('job_status', '!=', 'Batal')
                    ->where('status_pembayaran', 'DP')
                    ->orderByDesc('tgl_booking')
                    ->limit(30)
                    ->get(['booking_id', 'nama_client', 'tgl_booking', 'total_transaksi']);

                $newlyApproved = Booking::query()
                    ->where('booking_status', 'booked')
                    ->whereDate('tgl_booking', '>=', now()->subDays(30)->toDateString())
                    ->orderByDesc('tgl_booking')
                    ->limit(30)
                    ->get(['booking_id', 'nama_client', 'tgl_booking']);

                $notifItems = collect();

                foreach ($upcomingWeddings as $booking) {
                    $dayDiff = Carbon::parse($booking->tgl_acara)->diffInDays(today());
                    $notifItems->push([
                        'title' => 'Wedding terdekat',
                        'subtitle' => $booking->nama_client . ' • ' . Carbon::parse($booking->tgl_acara)->translatedFormat('d M Y'),
                        'time' => $dayDiff === 0 ? 'Hari ini' : 'H-' . $dayDiff,
                        'url' => route('admin2.pesanan.show', $booking->booking_id),
                        'type' => 'wedding',
                    ]);
                }

                foreach ($dpBookings as $booking) {
                    $notifItems->push([
                        'title' => 'Masih status DP',
                        'subtitle' => $booking->nama_client . ' • Rp ' . number_format((float) $booking->total_transaksi, 0, ',', '.'),
                        'time' => Carbon::parse($booking->tgl_booking)->translatedFormat('d M Y'),
                        'url' => route('admin2.pesanan.show', $booking->booking_id),
                        'type' => 'dp',
                    ]);
                }

                foreach ($newlyApproved as $booking) {
                    $notifItems->push([
                        'title' => 'Data baru di-approve',
                        'subtitle' => $booking->nama_client,
                        'time' => Carbon::parse($booking->tgl_booking)->diffForHumans(),
                        'url' => route('admin2.pesanan.show', $booking->booking_id),
                        'type' => 'approved',
                    ]);
                }

                $data = [
                    'admin2UpcomingWeddingCount' => Booking::where('job_status', '!=', 'Batal')->whereDate('tgl_acara', '>=', today())->count(),
                    'admin2DpCount' => Booking::where('job_status', '!=', 'Batal')->where('status_pembayaran', 'DP')->count(),
                    'admin2ApprovedCount' => Booking::where('booking_status', 'booked')->whereDate('tgl_booking', '>=', now()->subDays(30)->toDateString())->count(),
                    'admin2NotifItems' => $notifItems,
                ];
            } catch (\Throwable $e) {
                // Fail-safe: layout tetap jalan walaupun data notifikasi bermasalah.
            }

            $view->with($data);
        });
    }
}
