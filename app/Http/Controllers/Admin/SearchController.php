<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\PesananWebsite;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $bookings = collect();
        $customers = collect();
        $websiteOrders = collect();

        if ($q !== '') {
            $bookings = Booking::query()
                ->where(function ($query) use ($q) {
                    $query->where('nama_client', 'like', "%{$q}%")
                        ->orWhere('booking_status', 'like', "%{$q}%")
                        ->orWhere('status_pembayaran', 'like', "%{$q}%");

                    if (is_numeric($q)) {
                        $query->orWhere('booking_id', (int) $q);
                    }
                })
                ->orderByDesc('tgl_booking')
                ->limit(12)
                ->get();

            $customers = Customer::query()
                ->where('nama_client', 'like', "%{$q}%")
                ->orWhere('no_wa', 'like', "%{$q}%")
                ->orderBy('nama_client')
                ->limit(12)
                ->get();

            $websiteOrders = PesananWebsite::query()
                ->where(function ($query) use ($q) {
                    $query->where('nama_pemesan', 'like', "%{$q}%")
                        ->orWhere('no_wa', 'like', "%{$q}%")
                        ->orWhere('status', 'like', "%{$q}%");

                    if (is_numeric($q)) {
                        $query->orWhere('pesanan_id', (int) $q);
                    }
                })
                ->orderByDesc('created_at')
                ->limit(12)
                ->get();
        }

        return view('admin.search.index', [
            'query' => $q,
            'bookings' => $bookings,
            'customers' => $customers,
            'websiteOrders' => $websiteOrders,
        ]);
    }
}
