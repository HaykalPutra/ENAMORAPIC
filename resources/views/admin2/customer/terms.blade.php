@extends('layouts.admin2')
@section('title', 'Lihat Termin Customer')
@section('hideTopbarTitle', '1')

@section('styles')
<style>
.term-card {
    border: 1px solid #e7edf8;
    border-radius: 14px;
    transition: all .3s ease;
    background: #ffffff;
    box-shadow: 0 4px 12px rgba(11,23,54,.04);
}
.term-card.active {
    border-color: #1a2f6a;
    background: #f7f9fd;
    box-shadow: 0 4px 16px rgba(26,47,106,.1);
}
.term-card.paid {
    border-color: #22c55e;
    background: #f0fdf4;
    box-shadow: 0 4px 12px rgba(34,197,94,.08);
}
.badge-paid { background: #22c55e; color: white; }
.badge-unpaid { background: #f59e0b; color: white; }
.badge-overdue { background: #ef4444; color: white; }
.progress-payment { height: 8px; border-radius: 4px; background: #e7edf8; }
.readonly-note {
    background: linear-gradient(135deg,#fff8e1 0%,#fffbeb 100%);
    border: 1px solid #fcd34d;
    border-radius: 12px;
}
.payment-card {
    background:#ffffff;
    border:1px solid #e7edf8;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(11,23,54,.07);
}
</style>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin2.customer.show', $customer->customer_id) }}" class="btn" style="background:#f0f4f9;color:#1a2f6a;border:1px solid #e0e8f3;" title="Kembali">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h3 class="fw-bold mb-0 text-dark"><i class="bi bi-credit-card-2-front me-2" style="color:#d4af37;"></i>Lihat Termin Customer</h3>
        <p class="text-secondary mb-0 small">Halaman ini hanya untuk melihat dan mengirim link pembayaran ke customer.</p>
    </div>
</div>

<div class="readonly-note p-3 mb-4">
    <i class="bi bi-eye me-1" style="color:#d97706;"></i>
    <strong style="color:#d97706;">Mode Read-only:</strong> <span style="color:#92400e;">Admin2 tidak dapat melakukan pembayaran atau mengubah status termin.</span>
</div>

<div class="payment-card mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-4 pb-3" style="border-bottom:1px solid #e7edf8;">
            <div>
                <h5 class="fw-bold mb-1" style="color:#1a2f6a;">Pembayaran Booking #{{ $booking->booking_id }}</h5>
                <span class="text-secondary" style="font-size:.9rem;">{{ $customer->nama_client }}</span>
            </div>
            <span class="badge bg-{{ $booking->status_badge }} rounded-pill px-3 py-2">{{ $booking->job_status }}</span>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-sm-6">
                <small class="text-secondary d-block mb-1" style="text-transform:uppercase;font-weight:700;font-size:.7rem;letter-spacing:.06em;">Paket</small>
                <strong style="color:#1a2f6a;">{{ optional($booking->details->first())->paket->nama_paket ?? '-' }}</strong>
            </div>
            <div class="col-sm-6">
                <small class="text-secondary d-block mb-1" style="text-transform:uppercase;font-weight:700;font-size:.7rem;letter-spacing:.06em;">Tanggal Acara</small>
                <strong style="color:#1a2f6a;">{{ \Carbon\Carbon::parse($booking->tgl_acara)->format('d M Y') }}</strong>
            </div>
            <div class="col-sm-6">
                <small class="text-secondary d-block mb-1" style="text-transform:uppercase;font-weight:700;font-size:.7rem;letter-spacing:.06em;">Total Harga</small>
                <strong style="color:#1a2f6a;font-size:1.2rem;">Rp {{ number_format((float)$booking->total_transaksi, 0, ',', '.') }}</strong>
            </div>
            <div class="col-sm-6">
                <small class="text-secondary d-block mb-1" style="text-transform:uppercase;font-weight:700;font-size:.7rem;letter-spacing:.06em;">Sisa Bayar</small>
                <strong style="color:#ef4444;font-size:1.2rem;">Rp {{ number_format((float)$paymentSummary['remaining'], 0, ',', '.') }}</strong>
            </div>
        </div>

        @php
            $paidPct = $paymentSummary['total_amount'] > 0 ? round(($paymentSummary['total_paid'] / $paymentSummary['total_amount']) * 100) : 0;
        @endphp
        <div>
            <div class="d-flex justify-content-between small mb-2">
                <span style="color:#6f819e;">Sudah dibayar: <strong style="color:#1a2f6a;">Rp {{ number_format((float)$paymentSummary['total_paid'], 0, ',', '.') }}</strong></span>
                <span style="color:#6f819e;font-weight:700;">{{ $paidPct }}%</span>
            </div>
            <div class="progress progress-payment">
                <div class="progress-bar bg-success" style="width: {{ $paidPct }}%"></div>
            </div>
        </div>
    </div>
</div>

<div class="payment-card mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4" style="color:#1a2f6a;"><i class="bi bi-list-check me-2" style="color:#d4af37;"></i>Jadwal Pembayaran ({{ $allTerms->count() }} Termin)</h5>
        @foreach($allTerms as $term)
        <div class="term-card p-4 mb-3 {{ $term->isPaid() ? 'paid' : ($currentTerm && $currentTerm->id === $term->id ? 'active' : '') }}">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong style="color:#1a2f6a;font-size:1rem;">Termin {{ $term->term_number }}</strong>
                    <span class="text-secondary ms-2" style="font-size:.85rem;">({{ (int)$term->term_percentage }}%)</span>
                    <br>
                    <small class="text-secondary mt-1" style="display:block;">
                        <i class="bi bi-calendar3 me-1"></i>
                        Jatuh tempo: {{ \Carbon\Carbon::parse($term->due_date)->format('d M Y') }}
                    </small>
                </div>
                <div class="text-end">
                    <strong class="d-block" style="color:#1a2f6a;font-size:1.1rem;">Rp {{ number_format((float)$term->term_amount, 0, ',', '.') }}</strong>
                    @if($term->isPaid())
                        <span class="badge badge-paid rounded-pill mt-2"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                    @elseif($term->isOverdue())
                        <span class="badge badge-overdue rounded-pill mt-2"><i class="bi bi-exclamation-circle me-1"></i>Overdue</span>
                    @else
                        <span class="badge badge-unpaid rounded-pill mt-2"><i class="bi bi-clock me-1"></i>Belum Bayar</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="payment-card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4" style="color:#1a2f6a;"><i class="bi bi-send me-2" style="color:#d4af37;"></i>Kirim Link Pembayaran ke Customer</h5>
        <div style="background:#f7f9fd;border:1px solid #e7edf8;border-radius:12px;padding:16px;margin-bottom:20px;">
            <small class="text-secondary d-block mb-2" style="text-transform:uppercase;font-weight:700;font-size:.7rem;letter-spacing:.06em;">Link pembayaran customer</small>
            <a href="{{ $customerPaymentUrl }}" target="_blank" class="fw-bold text-primary text-break" style="word-break:break-all;" title="Buka link pembayaran">{{ $customerPaymentUrl }}</a>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($shareMessage) }}" target="_blank" class="btn btn-success fw-bold">
                <i class="bi bi-whatsapp me-2"></i>Kirim via WhatsApp
            </a>
            <a href="{{ $customerPaymentUrl }}" target="_blank" class="btn fw-bold" style="background:#f0f4f9;color:#1a2f6a;border:1px solid #e0e8f3;">
                <i class="bi bi-box-arrow-up-right me-2"></i>Buka Link Customer
            </a>
        </div>
    </div>
</div>
@endsection
