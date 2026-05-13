<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // Keep customer master in sync with booking transactions.
        $this->syncCustomersFromBookings();

        $query = Customer::withCount('bookings')
            ->withMax('bookings', 'booking_id')
            ->with([
                'bookings' => function ($q) {
                    $q->with(['details.paket', 'paymentTerms'])->orderByDesc('booking_id');
                }
            ]);
        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_client', 'like', '%' . $search . '%')
                    ->orWhere('no_wa', 'like', '%' . $search . '%');
            });
        }

        // Prioritize customers with latest booking activity so booking growth is visible.
        $customers = $query->orderByDesc('bookings_max_booking_id')
            ->orderByDesc('customer_id')
            ->paginate(20);

        $latestBookingPerCustomer = Booking::query()
            ->selectRaw('MAX(booking_id) as latest_booking_id, customer_id')
            ->whereNotNull('customer_id')
            ->groupBy('customer_id');

        $latestBookings = Booking::query()
            ->joinSub($latestBookingPerCustomer, 'latest_booking', function ($join) {
                $join->on('booking.booking_id', '=', 'latest_booking.latest_booking_id');
            })
            ->leftJoin('payment_terms', 'payment_terms.booking_id', '=', 'booking.booking_id')
            ->selectRaw('booking.booking_id, SUM(CASE WHEN payment_terms.payment_status = "paid" THEN 1 ELSE 0 END) as paid_terms')
            ->groupBy('booking.booking_id')
            ->get();

        $dpCustomers = $latestBookings->filter(function ($row) {
            return (int) $row->paid_terms === 1;
        })->count();

        $lunasCustomers = $latestBookings->filter(function ($row) {
            return (int) $row->paid_terms >= 2;
        })->count();

        $stats = [
            'totalCustomers' => Customer::count(),
            'totalBookings' => Booking::count(),
            'dpCustomers' => $dpCustomers,
            'lunasCustomers' => $lunasCustomers,
        ];

        return view('admin.customer.index', compact('customers', 'stats'));
    }

    /**
     * Sync customer master from booking transactions.
     * Ensure each booking points to the correct customer identity (nama + no_wa).
     */
    private function syncCustomersFromBookings(): void
    {
        $bookings = Booking::with(['customer', 'pesananWebsite'])
            ->orderBy('booking_id')
            ->get();

        foreach ($bookings as $booking) {
            $customerName = trim((string) ($booking->nama_client ?: optional($booking->pesananWebsite)->nama_pemesan ?: 'Customer'));
            $sourceWa = optional($booking->customer)->no_wa
                ?? optional($booking->pesananWebsite)->no_wa
                ?? '-';
            $wa = $this->normalizeWhatsApp($sourceWa);

            $customer = Customer::firstOrCreate([
                'nama_client' => $customerName,
                'no_wa' => $wa,
            ], [
                'alamat' => 0,
            ]);

            if ((int) $booking->customer_id !== (int) $customer->customer_id) {
                $booking->update(['customer_id' => $customer->customer_id]);
            }
        }
    }

    private function normalizeWhatsApp(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if ($digits === '') {
            return '-';
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (!str_starts_with($digits, '62')) {
            return '62' . $digits;
        }

        return $digits;
    }

    public function show(Customer $customer)
    {
        $customer->load([
            'bookings' => function ($q) {
                $q->with(['details.paket', 'paymentTerms'])->orderByDesc('booking_id');
            }
        ]);
        return view('admin.customer.show', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customer.index')->with('success', 'Customer berhasil dihapus.');
    }
}
