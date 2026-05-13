<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Freelance;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class SearchAdmin2Controller extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $bookings = collect();
        $customers = collect();
        $teams = collect();

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

            $pegawai = Pegawai::query()
                ->where('nama', 'like', "%{$q}%")
                ->orWhere('role', 'like', "%{$q}%")
                ->limit(6)
                ->get(['id', 'nama', 'role']);

            $freelance = Freelance::query()
                ->where('nama', 'like', "%{$q}%")
                ->orWhere('role', 'like', "%{$q}%")
                ->limit(6)
                ->get(['id', 'nama', 'role']);

            $teams = $pegawai
                ->map(fn ($item) => ['type' => 'Pegawai', 'id' => $item->id, 'nama' => $item->nama, 'role' => $item->role])
                ->merge($freelance->map(fn ($item) => ['type' => 'Freelance', 'id' => $item->id, 'nama' => $item->nama, 'role' => $item->role]))
                ->take(12);
        }

        return view('admin2.search.index', [
            'query' => $q,
            'bookings' => $bookings,
            'customers' => $customers,
            'teams' => $teams,
        ]);
    }
}
