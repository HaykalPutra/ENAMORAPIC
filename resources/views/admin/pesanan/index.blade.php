@extends('layouts.admin')
@section('title', 'Data Pesanan')
@section('hideTopbarTitle', '1')

@section('content')
<style>
/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-15px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.booking-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%);
    animation: fadeInUp 0.5s ease forwards;
    color: #1a2332;
}

.booking-card:nth-child(1) { animation-delay: 0.05s; }
.booking-card:nth-child(2) { animation-delay: 0.1s; }
.booking-card:nth-child(3) { animation-delay: 0.15s; }
.booking-card:nth-child(4) { animation-delay: 0.2s; }

.booking-card:hover {
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
    transform: translateY(-5px);
}

.booking-card .card-body {
    padding: 32px !important;
}

.booking-card h5 {
    color: #1a2332;
    font-weight: 800;
    letter-spacing: -0.5px;
    font-size: 1.35rem;
}

.booking-card .text-muted {
    color: #999 !important;
}

.status-badge {
    padding: 10px 20px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    text-transform: capitalize;
    animation: slideInLeft 0.4s ease;
    display: inline-block;
    border: none;
    transition: all 0.3s ease;
}

.status-badge.pending {
    background: rgba(255, 152, 0, 0.15);
    color: #ff9800;
}

.status-badge.booked {
    background: linear-gradient(135deg, rgba(0, 188, 212, 0.15) 0%, rgba(0, 150, 136, 0.15) 100%);
    color: #00bcd4;
    font-weight: 700;
}

.status-badge.selesai {
    background: rgba(76, 175, 80, 0.15);
    color: #4caf50;
}

.status-badge.batal {
    background: rgba(244, 67, 54, 0.15);
    color: #f44336;
}

.booking-card:hover .status-badge {
    transform: scale(1.1);
}

.detail-label {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #999;
}

.detail-value {
    font-weight: 700;
    color: #1a2332;
    margin-top: 8px;
    font-size: 1rem;
}

.detail-value.price {
    color: #1976d2;
    font-size: 1.2rem;
}

.detail-value.dp-badge {
    display: inline-block;
    background: #ffc107;
    color: white;
    padding: 6px 14px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.85rem;
}

.detail-value.whatsapp-link {
    color: #25d366;
    text-decoration: none;
    font-weight: 700;
    transition: all 0.3s ease;
}

.detail-value.whatsapp-link:hover {
    color: #128c7e;
}

.action-btn {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: none;
    color: white;
    font-weight: 600;
}

.action-btn.btn-primary {
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
}

.action-btn.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(25, 118, 210, 0.3);
    background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
}

.action-btn.btn-info {
    background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
}

.action-btn.btn-info:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(0, 188, 212, 0.3);
    background: linear-gradient(135deg, #0097a7 0%, #006064 100%);
}

.filter-card {
    border-radius: 16px;
    border: none;
    background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%);
    color: #1a2332;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    animation: fadeInUp 0.5s ease;
}

.filter-card .form-select,
.filter-card .form-control {
    border-radius: 10px;
    border: 1.5px solid #e0e0e0;
    background-color: #ffffff;
    color: #1a2332;
    transition: all 0.3s ease;
    font-weight: 500;
}

.filter-card .form-select:focus,
.filter-card .form-control:focus {
    border-color: #1976d2;
    box-shadow: 0 0 0 4px rgba(25, 118, 210, 0.1);
}

.filter-card .form-label {
    color: #1a2332;
    font-weight: 700;
    font-size: 0.95rem;
}

.filter-card .btn-light {
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    border: none;
    color: #ffffff !important;
    font-weight: 700;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.filter-card .btn-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(25, 118, 210, 0.25);
    background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
}

.empty-state {
    animation: fadeInUp 0.5s ease;
}

.border-top-gold {
    border-top: 2px solid #e0e0e0 !important;
}

@media (max-width: 768px) {
    .booking-card {
        margin-bottom: 16px;
    }
    
    .action-btn {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }
    
    .booking-card h5 {
        font-size: 1.1rem;
    }
    
    .detail-value {
        font-size: 0.95rem;
    }
}
</style>

<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="fw-bold mb-2" style="color: #1a2332;">
                <i class="bi bi-calendar-check" style="color: #d4af37; margin-right: 12px;"></i>Data Pesanan
            </h3>
            <p class="text-muted mb-0" style="font-weight: 500;">Kelola semua pesanan dari customer Anda dengan mudah</p>
        </div>
    </div>

    <div class="card filter-card mb-5">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="letter-spacing: 0.5px; color: #1a2332;">🔍 Cari Client</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama client..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold" style="letter-spacing: 0.5px; color: #1a2332;">📊 Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Pending" {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
                        <option value="Booked" {{ request('status')=='Booked'?'selected':'' }}>Booked</option>
                        <option value="Selesai" {{ request('status')=='Selesai'?'selected':'' }}>Selesai</option>
                        <option value="Batal" {{ request('status')=='Batal'?'selected':'' }}>Batal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold" style="letter-spacing: 0.5px; color: #1a2332;">📅 Bulan</label>
                    <select name="bulan" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ request('bulan')==$b?'selected':'' }}>
                            {{ \Carbon\Carbon::create()->month($b)->isoFormat('MMM') }}
                        </option>
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

    @if($bookings->count() == 0)
    <div class="text-center py-5 empty-state">
        <i class="bi bi-inbox" style="font-size: 5rem; color: rgba(212, 175, 55, 0.2); display: block;"></i>
        <p class="text-muted mt-4 fs-5 fw-500">Belum ada data pesanan</p>
    </div>
    @else
    <div class="row g-4 mt-1">
        @foreach($bookings as $b)
        <div class="col-lg-6">
            <div class="booking-card card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">{{ Str::limit($b->nama_client, 30) }}</h5>
                            <small class="text-muted">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $b->tgl_acara ? \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') : '-' }}
                            </small>
                        </div>
                        <span class="status-badge 
                            @if(strtolower($b->job_status) == 'pending') 
                                pending
                            @elseif(strtolower($b->job_status) == 'booked' || strtolower($b->job_status) == 'confirmed') 
                                booked
                            @elseif(strtolower($b->job_status) == 'selesai') 
                                selesai
                            @elseif(strtolower($b->job_status) == 'batal') 
                                batal
                            @endif">
                            {{ $b->job_status }}
                        </span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="detail-label">Paket</div>
                            <div class="detail-value small">
                                {{ Str::limit($b->display_paket_name, 20) }}
                            </div>
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

                    <div class="d-flex gap-2 justify-content-end pt-3 border-top-gold">
                        <a href="{{ route('admin.pesanan.show', ['id' => $b->booking_id, 'mode' => 'view']) }}" class="action-btn btn-primary" title="Lihat Detail (Read Only)">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.pesanan.show', ['id' => $b->booking_id, 'mode' => 'edit']) }}#update-status" class="action-btn btn-info" title="Edit Status">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($bookings->hasPages())
    <div class="mt-5 d-flex justify-content-center">
        {{ $bookings->withQueryString()->links() }}
    </div>
    @endif
    @endif
</div>

@endsection
