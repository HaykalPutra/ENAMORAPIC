<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerAdmin2Controller extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('bookings')
            ->withMax('bookings', 'booking_id')
            ->with([
                'bookings' => function ($q) {
                    $q->with(['details.paket', 'paymentTerms'])->orderByDesc('booking_id');
                }
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_client', 'like', '%' . $search . '%')
                  ->orWhere('no_wa', 'like', '%' . $search . '%');
            });
        }

        // Filter pembayaran
        $filterBayar = $request->get('filter_bayar', 'All');
        if ($filterBayar !== 'All') {
            $query->whereHas('bookings', function ($q) use ($filterBayar) {
                $q->where('status_pembayaran', $filterBayar);
            });
        }

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

        return view('admin2.customer.index', compact('customers', 'stats'));
    }

    public function show(Customer $customer)
    {
        $customer->load([
            'bookings' => function ($q) {
                $q->with(['details.paket', 'paymentTerms', 'customer'])->orderByDesc('booking_id');
            }
        ]);

        return view('admin2.customer.show', compact('customer'));
    }

    public function terms(Customer $customer, Booking $booking)
    {
        if ((int) $booking->customer_id !== (int) $customer->customer_id) {
            abort(404);
        }

        $booking->load(['details.paket', 'customer', 'paymentTerms']);

        $allTerms = $booking->paymentTerms()->orderBy('term_number')->get();
        $currentTerm = $booking->paymentTerms()
            ->whereIn('payment_status', ['unpaid', 'overdue'])
            ->orderBy('term_number')
            ->first();

        $totalPaid = (int) $allTerms->where('payment_status', 'paid')->sum('paid_amount');
        $paymentSummary = [
            'total_amount' => (int) $booking->total_transaksi,
            'total_paid' => $totalPaid,
            'remaining' => max(0, (int) $booking->total_transaksi - $totalPaid),
        ];

        $customerPaymentUrl = route('payment.show', $booking->booking_id);
        $waNumber = preg_replace('/[^0-9]/', '', $customer->no_wa ?? '');
        if (substr($waNumber, 0, 1) === '0') {
            $waNumber = '62' . substr($waNumber, 1);
        }

        $shareMessage = "Halo Kak {$customer->nama_client}, berikut link pembayaran booking #{$booking->booking_id}: {$customerPaymentUrl} . Silakan lanjutkan pembayaran termin berikutnya ya kak.";

        return view('admin2.customer.terms', [
            'customer' => $customer,
            'booking' => $booking,
            'allTerms' => $allTerms,
            'currentTerm' => $currentTerm,
            'paymentSummary' => $paymentSummary,
            'customerPaymentUrl' => $customerPaymentUrl,
            'waNumber' => $waNumber,
            'shareMessage' => $shareMessage,
        ]);
    }
}
