@extends('layouts.admin')

@section('title', 'Pencarian Global')

@section('content')
<div class="card-clean p-4 p-lg-5 mb-4" style="background:linear-gradient(120deg,#0b1736 0%,#152d61 60%,#1e3a8a 100%);color:#fff;overflow:hidden;position:relative;">
    <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(212,169,106,.35),transparent 72%);"></div>
    <h4 class="mb-2" style="font-weight:800;">Hasil Pencarian</h4>
    <p class="mb-0" style="color:rgba(255,255,255,.78);">
        @if($query !== '')
            Menampilkan hasil untuk: <strong>{{ $query }}</strong>
        @else
            Ketik kata kunci di search bar atas untuk mulai pencarian.
        @endif
    </p>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="card-clean p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0" style="font-weight:700;color:#0b1736;">Data Pesanan</h6>
                <span class="badge text-bg-primary">{{ $bookings->count() }}</span>
            </div>
            @forelse($bookings as $booking)
                <a href="{{ route('admin.pesanan.show', $booking->booking_id) }}" class="d-block text-decoration-none p-2 rounded-3 mb-2" style="background:#f6f8fc;">
                    <div class="fw-bold text-dark">#{{ $booking->booking_id }} - {{ $booking->nama_client }}</div>
                    <small class="text-muted">{{ $booking->status_pembayaran }} • {{ $booking->booking_status ?? '-' }}</small>
                </a>
            @empty
                <div class="text-muted small">Belum ada data cocok.</div>
            @endforelse
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card-clean p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0" style="font-weight:700;color:#0b1736;">Customer</h6>
                <span class="badge text-bg-success">{{ $customers->count() }}</span>
            </div>
            @forelse($customers as $customer)
                <a href="{{ route('admin.customer.show', $customer->customer_id) }}" class="d-block text-decoration-none p-2 rounded-3 mb-2" style="background:#f6f8fc;">
                    <div class="fw-bold text-dark">{{ $customer->nama_client }}</div>
                    <small class="text-muted">{{ $customer->no_wa ?: '-' }}</small>
                </a>
            @empty
                <div class="text-muted small">Belum ada data cocok.</div>
            @endforelse
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card-clean p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0" style="font-weight:700;color:#0b1736;">Pesanan Website</h6>
                <span class="badge text-bg-warning">{{ $websiteOrders->count() }}</span>
            </div>
            @forelse($websiteOrders as $order)
                <a href="{{ route('admin.pesanan-website') }}" class="d-block text-decoration-none p-2 rounded-3 mb-2" style="background:#f6f8fc;">
                    <div class="fw-bold text-dark">#{{ $order->pesanan_id }} - {{ $order->nama_pemesan }}</div>
                    <small class="text-muted">{{ $order->status }} • {{ optional($order->created_at)->format('d M Y H:i') }}</small>
                </a>
            @empty
                <div class="text-muted small">Belum ada data cocok.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
