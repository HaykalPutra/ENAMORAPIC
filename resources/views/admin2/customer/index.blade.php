@extends('layouts.admin2')
@section('title','Data Customer')
@section('hideTopbarTitle', '1')

@section('content')
<style>
    .customer-stat-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 10px 24px rgba(21, 33, 54, 0.08);
        transition: transform .25s ease, box-shadow .25s ease;
        overflow: hidden;
    }

    .customer-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(21, 33, 54, 0.14);
    }

    .customer-stat-card .card-body {
        padding: 18px 20px;
    }

    .customer-stat-label {
        display: block;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: .73rem;
        color: #6c7a91;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .customer-stat-value {
        font-size: 1.9rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .customer-stat-total {
        background: linear-gradient(135deg, #ffffff 0%, #f6f9ff 100%);
        border: 1px solid #e9f0ff;
    }

    .customer-stat-booking {
        background: linear-gradient(135deg, #ffffff 0%, #f1f7ff 100%);
        border: 1px solid #e3ecff;
    }

    .customer-stat-dp {
        background: linear-gradient(135deg, #fffdf4 0%, #fff6d8 100%);
        border: 1px solid #ffe8a2;
    }

    .customer-stat-lunas {
        background: linear-gradient(135deg, #f4fff8 0%, #ddf8ea 100%);
        border: 1px solid #b7efd0;
    }

    .customer-data-table thead th {
        text-transform: uppercase;
        letter-spacing: .8px;
        font-size: .78rem;
        color: #5f6f89;
        background: linear-gradient(180deg, #f8fbff 0%, #f2f7ff 100%);
        border-bottom: 1px solid #e7eefb;
        padding-top: 15px;
        padding-bottom: 15px;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .customer-data-table tbody td {
        vertical-align: middle;
        padding-top: 13px;
        padding-bottom: 13px;
        border-color: #eef3fb;
    }

    .customer-data-table tbody tr {
        transition: background-color .2s ease, transform .2s ease;
    }

    .customer-data-table tbody tr:nth-child(even) {
        background: #fcfdff;
    }

    .customer-data-table tbody tr:hover {
        background: #f2f7ff;
    }

    .customer-table-shell {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(21, 33, 54, 0.08);
    }

    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a2332 0%, #2d3e50 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 5px 12px rgba(26, 35, 50, 0.25);
    }

    .customer-termin-badge {
        background: linear-gradient(135deg, #e9f8ff 0%, #c8efff 100%);
        color: #075985;
        border: 1px solid #b3e7ff;
        border-radius: 999px;
        padding: 6px 12px;
        font-weight: 700;
        font-size: .76rem;
    }

    .customer-booking-badge {
        background: linear-gradient(135deg, #2f80ed 0%, #1f6fd1 100%);
        border-radius: 999px;
        padding: 6px 12px;
        font-weight: 700;
        font-size: .76rem;
        box-shadow: 0 6px 12px rgba(31, 111, 209, 0.22);
    }

    .customer-action-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all .2s ease;
    }

    .customer-action-btn:hover {
        transform: translateY(-1px);
    }

    .customer-pagination .pagination {
        margin-bottom: 0;
        gap: 6px;
    }

    .customer-pagination .page-link {
        border: 1px solid #dbe6f7;
        color: #27406a;
        border-radius: 10px;
        min-width: 40px;
        text-align: center;
        font-weight: 600;
        padding: 8px 12px;
    }

    .customer-pagination .page-item.active .page-link {
        background: #1f6fd1;
        border-color: #1f6fd1;
        color: #fff;
    }

    .customer-pagination .page-item.disabled .page-link {
        color: #9aa9c2;
        background: #f4f7fc;
        border-color: #e2e9f5;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1a2332;">
            <i class="bi bi-people" style="color:#d4af37; margin-right:10px;"></i>Data Customer
        </h4>
        <p class="text-muted mb-0">Daftar semua client yang pernah booking</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card customer-stat-card customer-stat-total h-100">
            <div class="card-body">
                <span class="customer-stat-label">Total Customer</span>
                <div class="customer-stat-value" style="color:#1a2332;">{{ number_format($stats['totalCustomers'] ?? 0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card customer-stat-card customer-stat-booking h-100">
            <div class="card-body">
                <span class="customer-stat-label">Total Booking (Transaksi)</span>
                <div class="customer-stat-value text-primary">{{ number_format($stats['totalBookings'] ?? 0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card customer-stat-card customer-stat-dp h-100">
            <div class="card-body">
                <span class="customer-stat-label">Customer DP</span>
                <div class="customer-stat-value text-warning">{{ number_format($stats['dpCustomers'] ?? 0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card customer-stat-card customer-stat-lunas h-100">
            <div class="card-body">
                <span class="customer-stat-label">Customer Lunas</span>
                <div class="customer-stat-value text-success">{{ number_format($stats['lunasCustomers'] ?? 0) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4" style="background:linear-gradient(135deg,#1a2332,#2d3e50);border:none;">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label fw-bold text-white">Cari Customer</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-0"
                           value="{{ request('search') }}" placeholder="Cari nama atau no. WhatsApp...">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-light w-100 fw-bold text-primary">
                    <i class="bi bi-search me-1"></i>Cari
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card customer-table-shell">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table customer-data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Client</th>
                        <th>No. WhatsApp</th>
                        <th>Paket Terakhir</th>
                        <th>Progress Termin</th>
                        <th>Total Booking</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $no => $c)
                    @php
                        $wa = preg_replace('/[^0-9]/','', $c->no_wa ?? '');
                        if(substr($wa,0,1)=='0') $wa='62'.substr($wa,1);
                        $linkWA = "https://wa.me/{$wa}";
                        $latestBooking = $c->bookings->first();
                        $latestPaket = $latestBooking?->details->first()?->paket?->nama_paket ?? '-';
                        $totalTerms = $latestBooking?->paymentTerms?->count() ?? 0;
                        $paidTerms = $latestBooking?->paymentTerms?->where('payment_status', 'paid')->count() ?? 0;
                    @endphp
                    <tr>
                        <td class="ps-4 text-muted fw-bold">{{ $no + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="customer-avatar">
                                    {{ strtoupper(substr($c->nama_client, 0, 2)) }}
                                </div>
                                <strong class="text-dark">{{ $c->nama_client }}</strong>
                            </div>
                        </td>
                        <td>
                            <a href="{{ $linkWA }}" target="_blank" class="text-success text-decoration-none fw-bold">
                                <i class="bi bi-whatsapp me-1"></i>{{ $c->no_wa }}
                            </a>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $latestPaket }}</span>
                        </td>
                        <td>
                            @if($totalTerms > 0)
                            <span class="customer-termin-badge">{{ $paidTerms }}/{{ $totalTerms }} Termin</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge customer-booking-badge">
                                {{ $c->bookings_count }} Booking
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin2.customer.show', $c->customer_id) }}"
                               class="btn btn-outline-primary btn-sm customer-action-btn" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                            Belum ada data customer.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4 customer-pagination">
    {{ $customers->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
@endsection
