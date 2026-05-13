@extends('layouts.admin2')
@section('title','Daftar Pesanan')
@section('hideTopbarTitle', '1')

@section('styles')
<style>
.booking-card { border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s ease; background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%); color: #1a2332; }
.booking-card:hover { box-shadow: 0 16px 40px rgba(0,0,0,0.12); transform: translateY(-5px); }
.booking-card .card-body { padding: 32px !important; }

.status-badge { padding: 10px 20px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.5px; text-transform: capitalize; display: inline-block; }
.status-badge.pending { background: rgba(255, 152, 0, 0.15); color: #ff9800; }
.status-badge.booked { background: linear-gradient(135deg, rgba(0, 188, 212, 0.15) 0%, rgba(0, 150, 136, 0.15) 100%); color: #00bcd4; }
.status-badge.selesai { background: rgba(76, 175, 80, 0.15); color: #4caf50; }
.status-badge.batal { background: rgba(244, 67, 54, 0.15); color: #f44336; }

.detail-label { font-size: 0.65rem; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #999; }
.detail-value { font-weight: 700; color: #1a2332; margin-top: 8px; font-size: 1rem; }
.detail-value.price { color: #1976d2; font-size: 1.2rem; }
.detail-value.dp-badge { display: inline-block; background: #ffc107; color: white; padding: 6px 14px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; }

.action-btn { width: 44px; height: 44px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.3s ease; border: none; color: white; font-weight: 600; }
.action-btn.btn-primary { background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%); }
.action-btn.btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(25, 118, 210, 0.3); }

.filter-card { border-radius: 16px; border: none; background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%); color: #1a2332; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
.filter-card .form-select, .filter-card .form-control { border-radius: 10px; border: 1.5px solid #e0e0e0; background-color: #ffffff; color: #1a2332; font-weight: 500; }
.filter-card .btn-light { background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%); border: none; color: #fff !important; font-weight: 700; }

.border-top-gold { border-top: 2px solid #e0e0e0 !important; }

@media (max-width: 768px) {
    .action-btn { width: 40px; height: 40px; font-size: 0.9rem; }
}
</style>
@endsection

@section('content')
@php
    $activeRole = strtoupper((string) (auth()->user()->role ?? 'ADMIN'));
    $isCeo = $activeRole === 'CEO';
@endphp

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h3 class="fw-bold mb-2" style="color: #1a2332;">
            <i class="bi bi-calendar-check" style="color: #d4af37; margin-right: 12px;"></i>Data Pesanan
        </h3>
        <p class="text-muted mb-0" style="font-weight: 500;">Monitor data booking (read-only). Validasi status hanya oleh CEO.</p>
    </div>
    <div class="text-end">
        <span class="badge {{ $isCeo ? 'bg-primary' : 'bg-secondary' }} px-3 py-2">
            Role Aktif: {{ $activeRole }}
        </span>
    </div>
</div>

@if($isCeo)
<div class="alert alert-primary border-0 mb-4" style="border-radius:12px;">
    <i class="bi bi-stars me-2"></i>
    Anda login sebagai CEO. Halaman ini mode operasional (read-only). Untuk validasi/update pesanan gunakan menu CEO:
    <a href="{{ route('admin.pesanan') }}" class="alert-link fw-bold ms-1">Data Pesanan (CEO)</a>.
</div>
@else
<div class="alert alert-warning border-0 mb-4" style="border-radius:12px;">
    <i class="bi bi-shield-lock me-2"></i>
    Anda login sebagai ADMIN. Halaman ini hanya untuk lihat detail; validasi status dilakukan oleh CEO.
</div>
@endif

{{-- FILTER --}}
<div class="filter-card mb-5">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold">🔍 Cari Client</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama client..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">📊 Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(['Pending','Booked','Selesai','Batal'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">📅 Bulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($b)->isoFormat('MMM') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-light w-100 fw-bold btn-sm">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mt-1">
    @forelse($bookings as $b)
    @php
        $namaPaket = $b->display_paket_name;
        $badgeClass = strtolower($b->job_status);
    @endphp
    <div class="col-lg-6">
        <div class="booking-card card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ \Illuminate\Support\Str::limit($b->nama_client, 30) }}</h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar-event me-1"></i>
                            {{ $b->tgl_acara ? \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') : '-' }}
                        </small>
                    </div>
                    <span class="status-badge {{ $badgeClass }}">
                        {{ $b->job_status }}
                    </span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="detail-label">Paket</div>
                        <div class="detail-value small">{{ \Illuminate\Support\Str::limit($namaPaket, 20) }}</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Total</div>
                        <div class="detail-value price small">Rp {{ number_format((float) $b->total_transaksi,0,',','.') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="detail-label">Pembayaran</div>
                        @if($b->status_pembayaran == 'Lunas')
                            <div class="detail-value small">Lunas</div>
                        @else
                            <span class="detail-value dp-badge">DP</span>
                        @endif
                    </div>
                    <div class="col-6">
                        <div class="detail-label">ID</div>
                        <div class="detail-value small">#{{ $b->booking_id }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end pt-3 border-top-gold">
                    <a href="{{ route('admin2.pesanan.show', $b->booking_id) }}" class="action-btn btn-primary" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-light border text-center py-5">
            <i class="fas fa-search fa-3x mb-3 d-block text-muted opacity-50"></i>
            <h5 class="text-muted">Tidak ada data booking ditemukan.</h5>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $bookings->appends(request()->query())->links() }}
</div>
@endsection
