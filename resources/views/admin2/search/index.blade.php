@extends('layouts.admin2')

@section('title', 'Pencarian Global')

@section('content')
<div class="card p-4 p-lg-5 mb-4" style="background:linear-gradient(120deg,#1a2332 0%,#27354e 65%,#30466f 100%);color:#fff;overflow:hidden;position:relative;">
    <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(212,175,55,.32),transparent 72%);"></div>
    <h4 class="mb-2" style="font-weight:800;">Hasil Pencarian Admin 2</h4>
    <p class="mb-0" style="color:rgba(255,255,255,.78);">
        @if($query !== '')
            Menampilkan hasil untuk: <strong>{{ $query }}</strong>
        @else
            Ketik kata kunci pada search bar atas.
        @endif
    </p>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0" style="font-weight:700;color:#1a2332;">Booking</h6>
                <span class="badge text-bg-primary">{{ $bookings->count() }}</span>
            </div>
            @forelse($bookings as $booking)
                <a href="{{ route('admin2.pesanan.show', $booking->booking_id) }}" class="d-block text-decoration-none p-2 rounded-3 mb-2" style="background:#f6f8fc;">
                    <div class="fw-bold text-dark">#{{ $booking->booking_id }} - {{ $booking->nama_client }}</div>
                    <small class="text-muted">{{ $booking->status_pembayaran }} • {{ $booking->booking_status ?? '-' }}</small>
                </a>
            @empty
                <div class="text-muted small">Belum ada data cocok.</div>
            @endforelse
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0" style="font-weight:700;color:#1a2332;">Customer</h6>
                <span class="badge text-bg-success">{{ $customers->count() }}</span>
            </div>
            @forelse($customers as $customer)
                <a href="{{ route('admin2.customer.show', $customer->customer_id) }}" class="d-block text-decoration-none p-2 rounded-3 mb-2" style="background:#f6f8fc;">
                    <div class="fw-bold text-dark">{{ $customer->nama_client }}</div>
                    <small class="text-muted">{{ $customer->no_wa ?: '-' }}</small>
                </a>
            @empty
                <div class="text-muted small">Belum ada data cocok.</div>
            @endforelse
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0" style="font-weight:700;color:#1a2332;">Tim</h6>
                <span class="badge text-bg-warning">{{ $teams->count() }}</span>
            </div>
            @forelse($teams as $item)
                <div class="p-2 rounded-3 mb-2" style="background:#f6f8fc;">
                    <div class="fw-bold text-dark">{{ $item['nama'] ?: '-' }}</div>
                    <small class="text-muted">{{ $item['type'] }} • {{ $item['role'] ?: '-' }}</small>
                </div>
            @empty
                <div class="text-muted small">Belum ada data cocok.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
