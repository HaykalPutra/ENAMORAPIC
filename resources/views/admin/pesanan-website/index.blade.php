@extends('layouts.admin')
@section('title','Pesanan Website')
@section('hideTopbarTitle', '1')
@section('styles')
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

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.pulse {
    animation: pulse 2s infinite;
}

.website-order-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    margin-bottom: 24px;
    background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%);
    animation: fadeInUp 0.5s ease forwards;
    color: #1a2332;
}

.website-order-card:nth-child(1) { animation-delay: 0.05s; }
.website-order-card:nth-child(2) { animation-delay: 0.1s; }
.website-order-card:nth-child(3) { animation-delay: 0.15s; }
.website-order-card:nth-child(4) { animation-delay: 0.2s; }

.website-order-card:hover {
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
    transform: translateY(-5px);
}

.website-order-card .card-body {
    padding: 30px !important;
}

.kpi-card {
    border-radius: 14px;
    border: 1px solid rgba(25, 118, 210, 0.12);
    background: #fff;
    box-shadow: 0 10px 30px rgba(17, 40, 73, 0.08);
    padding: 16px 18px;
    height: 100%;
}

.kpi-label {
    font-size: 0.7rem;
    letter-spacing: 1.1px;
    text-transform: uppercase;
    color: #7d8ca1;
    font-weight: 700;
}

.kpi-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #1a2332;
    line-height: 1.1;
}

.kpi-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.filter-panel {
    background: #ffffff;
    border: 1px solid rgba(25, 118, 210, 0.12);
    border-radius: 14px;
    box-shadow: 0 10px 26px rgba(17, 40, 73, 0.08);
    padding: 16px;
}

.filter-panel .form-label {
    font-size: 0.68rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 700;
    color: #7d8ca1;
    margin-bottom: 6px;
}

.website-order-card h5 {
    color: #1a2332;
    font-weight: 800;
    letter-spacing: -0.5px;
    font-size: 1.35rem;
}

.website-order-card .text-muted {
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

.status-badge.done {
    background: linear-gradient(135deg, rgba(76, 175, 80, 0.15) 0%, rgba(56, 142, 60, 0.15) 100%);
    color: #4caf50;
    font-weight: 700;
}

.status-badge.all {
    background: rgba(25, 118, 210, 0.12);
    color: #1976d2;
}

.website-order-card:hover .status-badge {
    transform: scale(1.1);
}

.detail-label {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #999;
    margin-bottom: 6px;
}

.detail-value {
    font-weight: 700;
    color: #1a2332;
    margin-top: 4px;
    font-size: 1rem;
    line-height: 1.45;
}

.detail-value.price {
    color: #1976d2;
    font-size: 1.15rem;
}

.detail-value.dp-badge {
    display: inline-block;
    background: #ffc107;
    color: white;
    padding: 4px 12px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.8rem;
}

.detail-value.lunas-badge {
    display: inline-block;
    background: #4caf50;
    color: white;
    padding: 4px 12px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.8rem;
}

.detail-value.whatsapp-link {
    color: #25d366;
    text-decoration: none;
    font-weight: 700;
    transition: all 0.3s ease;
    display: inline-block;
}

.detail-value.whatsapp-link:hover {
    color: #128c7e;
    transform: translateX(3px);
}

.detail-value.location {
    color: #1a2332;
    font-size: 0.95rem;
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

.badge-new {
    background: linear-gradient(135deg, #f44336 0%, #e53935 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    display: inline-block;
}

.empty-state {
    animation: fadeInUp 0.5s ease;
}

.border-top-gold {
    border-top: 2px solid #f0f0f0 !important;
    margin-top: 8px;
}

.new-badge {
    animation: pulse 2s infinite;
}

.detail-block {
    background: rgba(25, 118, 210, 0.04);
    border: 1px solid rgba(25, 118, 210, 0.08);
    border-radius: 12px;
    padding: 12px 14px;
    height: 100%;
}

@media (max-width: 768px) {
    .website-order-card {
        margin-bottom: 18px;
    }
    
    .action-btn {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }
    
    .website-order-card h5 {
        font-size: 1.1rem;
    }
    
    .detail-value {
        font-size: 0.95rem;
    }

    .website-order-card .card-body {
        padding: 22px !important;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-2" style="color: #1a2332;">
                <i class="bi bi-globe" style="color: #1976d2; margin-right: 12px;"></i>Pesanan dari Website
            </h3>
            <p class="text-muted mb-0" style="font-weight: 500;">Notifikasi booking masuk dari form website</p>
        </div>
        @if(($kpis['pending'] ?? 0) > 0)
        <span class="badge-new">
            <i class="bi bi-circle-fill new-badge me-1" style="font-size:0.5rem;"></i> {{ $kpis['pending'] ?? 0 }} Perlu Validasi
        </span>
        @endif
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Pending Validasi</div>
                        <div class="kpi-value">{{ $kpis['pending'] ?? 0 }}</div>
                    </div>
                    <span class="kpi-icon" style="background: rgba(255, 152, 0, 0.14); color: #ff9800;">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Masuk Hari Ini</div>
                        <div class="kpi-value">{{ $kpis['newToday'] ?? 0 }}</div>
                    </div>
                    <span class="kpi-icon" style="background: rgba(25, 118, 210, 0.14); color: #1976d2;">
                        <i class="bi bi-calendar2-plus"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Tervalidasi Hari Ini</div>
                        <div class="kpi-value">{{ $kpis['validatedToday'] ?? 0 }}</div>
                    </div>
                    <span class="kpi-icon" style="background: rgba(76, 175, 80, 0.14); color: #4caf50;">
                        <i class="bi bi-check2-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Total Minggu Ini</div>
                        <div class="kpi-value">{{ $kpis['weekTotal'] ?? 0 }}</div>
                    </div>
                    <span class="kpi-icon" style="background: rgba(156, 39, 176, 0.14); color: #8e24aa;">
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.pesanan-website') }}" method="GET" class="filter-panel mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label">Cari Data</label>
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Nama, No WA, atau ID Booking">
            </div>
            <div class="col-lg-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ ($status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="done" {{ ($status ?? '') === 'done' ? 'selected' : '' }}>Selesai Diproses</option>
                    <option value="all" {{ ($status ?? '') === 'all' ? 'selected' : '' }}>Semua</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label">Tanggal Acara</label>
                <input type="date" name="date" value="{{ $date ?? '' }}" class="form-control">
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('admin.pesanan-website') }}" class="btn btn-outline-secondary w-100 fw-semibold">Reset</a>
            </div>
        </div>
    </form>

    @if($pesanans->count() == 0)
    <div class="text-center py-5 empty-state">
        <i class="bi bi-inbox" style="font-size: 5rem; color: rgba(0, 0, 0, 0.1); display: block;"></i>
        <p class="text-muted mt-4 fs-5 fw-500">Tidak ada data yang cocok dengan filter saat ini</p>
    </div>
    @else
    <div class="row g-4">
        @foreach($pesanans as $p)
        @php
            $wa = preg_replace('/[^0-9]/','', $p->no_wa ?? '');
            if(substr($wa,0,1)=='0') $wa='62'.substr($wa,1);
            $msg = "Halo Kak {$p->nama_pemesan}, kami dari Enamorapic ingin mengkonfirmasi pesanan Anda. Mohon konfirmasi lebih lanjut ya 😊";
            $linkWA = "https://wa.me/{$wa}?text=".urlencode($msg);
        @endphp
        <div class="col-lg-6 d-flex">
            <div class="card website-order-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">{{ $p->nama_pemesan }}</h5>
                            <small class="text-muted">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $p->tanggal_booking ? \Carbon\Carbon::parse($p->tanggal_booking)->format('d M Y') : '-' }}
                                @if($p->booking_id)
                                <span class="ms-2 text-primary fw-bold">#{{ $p->booking_id }}</span>
                                @endif
                            </small>
                        </div>
                        <span class="status-badge {{ $p->status === 'done' ? 'done' : 'pending' }}">
                            <i class="bi {{ $p->status === 'done' ? 'bi-check-circle' : 'bi-clock' }} me-1"></i>
                            {{ $p->status === 'done' ? 'Selesai Diproses' : 'Menunggu Validasi' }}
                        </span>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span class="badge-new">
                            <i class="bi bi-star-fill new-badge me-1"></i>
                            {{ $p->status === 'done' ? 'Sudah Diproses' : 'Pesanan Baru' }}
                        </span>
                        <small class="text-muted">Masuk: {{ optional($p->created_at)->format('d M Y H:i') }}</small>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="detail-block">
                                <div class="detail-label">Paket</div>
                                <div class="detail-value">{{ $p->paket->nama_paket ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-block">
                                <div class="detail-label">WhatsApp</div>
                                <div>
                                    <a href="{{ $linkWA }}" target="_blank" class="detail-value whatsapp-link">
                                        <i class="bi bi-whatsapp"></i> {{ $p->no_wa }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-block">
                                <div class="detail-label">💰 Total Harga</div>
                                @if($p->booking)
                                <div class="detail-value price">Rp {{ number_format((float)$p->booking->total_transaksi, 0, ',', '.') }}</div>
                                @else
                                <div class="detail-value">Rp {{ number_format((float)($p->paket->harga ?? 0), 0, ',', '.') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-block">
                                <div class="detail-label">💳 Status Bayar</div>
                                @if($p->booking)
                                <div class="mt-2">
                                    <span class="detail-value {{ $p->booking->status_pembayaran == 'Lunas' ? 'lunas-badge' : 'dp-badge' }}">
                                        {{ $p->booking->status_pembayaran }}
                                    </span>
                                </div>
                                @else
                                <div class="detail-value">-</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-block">
                                <div class="detail-label">📍 Lokasi Acara</div>
                                <div class="detail-value location">{{ $p->lokasi ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top-gold">
                        @if($p->status === 'pending')
                        <form action="{{ route('admin.pesanan-website.validate', $p->pesanan_id) }}" method="POST" class="flex-fill">
                            @csrf @method('PUT')
                            <input type="hidden" name="action" value="booked">
                            <button type="submit" class="btn w-100 py-2 fw-bold js-validate-action"
                                    data-action-label="Booked"
                                    data-confirm-text="Status booking akan berubah menjadi Booked dan pesanan website akan diproses."
                                    data-confirm-icon="question"
                                    style="background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%); color: white; border: none; border-radius: 10px;">
                                <i class="bi bi-check-circle me-1"></i>Booked
                            </button>
                        </form>
                        <form action="{{ route('admin.pesanan-website.validate', $p->pesanan_id) }}" method="POST" class="flex-fill">
                            @csrf @method('PUT')
                            <input type="hidden" name="action" value="batal">
                            <button type="submit" class="btn w-100 py-2 fw-bold js-validate-action"
                                    data-action-label="Tolak"
                                    data-confirm-text="Status booking akan berubah menjadi Batal (Ditolak) dan pesanan website akan diproses."
                                    data-confirm-icon="warning"
                                    style="background: linear-gradient(135deg, #f44336 0%, #e53935 100%); color: white; border: none; border-radius: 10px;">
                                <i class="bi bi-x-circle me-1"></i>Tolak
                            </button>
                        </form>
                        @else
                        <div class="w-100 p-2 text-center rounded-3" style="background: rgba(76, 175, 80, 0.1); color:#2e7d32; font-weight:700;">
                            <i class="bi bi-check2-all me-1"></i>Pesanan ini sudah tervalidasi
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($pesanans->hasPages())
    <div class="mt-5 d-flex justify-content-center">
        {{ $pesanans->links() }}
    </div>
    @endif
    @endif
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.js-validate-action');

    buttons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const form = button.closest('form');
            const actionLabel = button.dataset.actionLabel || 'Validasi';
            const confirmText = button.dataset.confirmText || 'Lanjutkan proses validasi pesanan ini?';
            const confirmIcon = button.dataset.confirmIcon || 'question';

            Swal.fire({
                title: 'Konfirmasi ' + actionLabel,
                text: confirmText,
                icon: confirmIcon,
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                confirmButtonColor: '#1a2332',
                cancelButtonColor: '#9aa0a6'
            }).then(function (result) {
                if (result.isConfirmed) {
                    button.disabled = true;
                    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
