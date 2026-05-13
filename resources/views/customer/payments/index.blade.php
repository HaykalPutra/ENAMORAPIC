<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pembayaran — Enamorapic</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
:root { --primary-dark: #1a2332; --gold-accent: #d4af37; }
body { font-family: 'Inter', sans-serif; background: #f4f6f9; min-height: 100vh; }
h1,h2,h3,h4,h5,.serif { font-family: 'Playfair Display', serif; }
.topbar { background: var(--primary-dark); padding: 18px 0; }
.card-payment { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
.term-card { border: 1.5px solid #e2e8f0; border-radius: 12px; transition: all .3s ease; }
.term-card.active { border-color: var(--primary-dark); background: #f8fafc; }
.term-card.paid { border-color: #22c55e; background: #f0fdf4; }
.badge-paid { background: #22c55e; }
.badge-unpaid { background: #f59e0b; }
.badge-overdue { background: #ef4444; }
.btn-pay { background: var(--primary-dark); color: #fff; border-radius: 50px; padding: 12px 40px; font-weight: 600; letter-spacing: .5px; transition: all .35s; }
.btn-pay:hover { background: #2d3e50; color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(26,35,50,.25); }
.progress-payment { height: 10px; border-radius: 5px; }
</style>
</head>
<body>

<div class="topbar text-center">
    <a href="{{ route('home') }}" class="text-white text-decoration-none">
        <img src="{{ asset('assets/images/enamora.png') }}" alt="Logo Enamora" loading="eager" style="height:42px; width:auto; object-fit:contain;" onerror="this.src='{{ asset('assets/images/enamora.svg') }}'">
    </a>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- BOOKING INFO --}}
            <div class="card card-payment mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="serif fw-bold mb-1">Pembayaran Booking</h4>
                            <span class="text-muted">Booking #{{ $booking->booking_id }}</span>
                        </div>
                        <span class="badge bg-{{ $booking->status_badge }} rounded-pill px-3 py-2">
                            {{ $booking->job_status }}
                        </span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Nama Client</small>
                            <strong>{{ $booking->nama_client }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Paket</small>
                            <strong>{{ optional($booking->details->first())->paket->nama_paket ?? '-' }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Tanggal Acara</small>
                            <strong>{{ \Carbon\Carbon::parse($booking->tgl_acara)->format('d M Y') }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Total Harga</small>
                            <strong class="text-primary fs-5">Rp {{ number_format((float)$booking->total_transaksi, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    {{-- PROGRESS BAR --}}
                    @php
                        $paidPct = $paymentSummary['total_amount'] > 0 
                            ? round(($paymentSummary['total_paid'] / $paymentSummary['total_amount']) * 100) 
                            : 0;
                    @endphp
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Sudah dibayar: <strong>Rp {{ number_format((float)$paymentSummary['total_paid'], 0, ',', '.') }}</strong></span>
                            <span>{{ $paidPct }}%</span>
                        </div>
                        <div class="progress progress-payment">
                            <div class="progress-bar bg-success" style="width: {{ $paidPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TERMIN LIST --}}
            <div class="card card-payment mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2"></i>Jadwal Pembayaran ({{ $allTerms->count() }} Termin)</h5>

                    @foreach($allTerms as $term)
                    <div class="term-card p-3 mb-3 {{ $term->isPaid() ? 'paid' : ($currentTerm && $currentTerm->id === $term->id ? 'active' : '') }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Termin {{ $term->term_number }}</strong>
                                <span class="text-muted ms-2">({{ (int)$term->term_percentage }}%)</span>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Jatuh tempo: {{ \Carbon\Carbon::parse($term->due_date)->format('d M Y') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <strong class="d-block">Rp {{ number_format((float)$term->term_amount, 0, ',', '.') }}</strong>
                                @if($term->isPaid())
                                    <span class="badge badge-paid text-white rounded-pill mt-1">
                                        <i class="bi bi-check-circle me-1"></i>Lunas
                                    </span>
                                @elseif($term->isOverdue())
                                    <span class="badge badge-overdue text-white rounded-pill mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>Overdue
                                    </span>
                                @else
                                    <span class="badge badge-unpaid text-white rounded-pill mt-1">
                                        <i class="bi bi-clock me-1"></i>Belum Bayar
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- BAYAR SEKARANG --}}
            @if($currentTerm)
            <div class="card card-payment">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-credit-card me-2"></i>Bayar Termin {{ $currentTerm->term_number }}</h5>
                    
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="d-flex justify-content-between">
                            <span>Jumlah yang harus dibayar:</span>
                            <strong class="fs-5 text-primary">Rp {{ number_format((float)$currentTerm->term_amount, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <form action="{{ route('payment.initiate', $booking->booking_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="term_id" value="{{ $currentTerm->id }}">

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Metode Pembayaran</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="payment_method" id="method_qris" value="qris" checked>
                                    <label class="btn btn-outline-dark w-100 py-3 rounded-3" for="method_qris">
                                        <i class="bi bi-qr-code-scan d-block fs-4 mb-1"></i>
                                        <small>QRIS</small>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="payment_method" id="method_transfer" value="transfer">
                                    <label class="btn btn-outline-dark w-100 py-3 rounded-3" for="method_transfer">
                                        <i class="bi bi-bank d-block fs-4 mb-1"></i>
                                        <small>Transfer Bank</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-pay w-100">
                            <i class="bi bi-shield-lock me-2"></i>Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- KONFIRMASI KE ADMIN VIA WHATSAPP --}}
            @if($hasPaidTerm)
            <div class="card card-payment mt-4">
                <div class="card-body p-4 text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-whatsapp text-success fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Konfirmasi Pembayaran</h5>
                    <p class="text-muted small mb-3">Sudah melakukan pembayaran? Kirim konfirmasi ke admin agar segera divalidasi.</p>
                    <a href="https://wa.me/{{ $adminPhone }}?text={{ urlencode($waMessage) }}"
                       target="_blank"
                       class="btn w-100 py-3 fw-bold rounded-pill"
                       style="background: #25D366; color: #fff; font-size: 1.05rem;">
                        <i class="bi bi-whatsapp me-2"></i>Konfirmasi via WhatsApp
                    </a>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle me-1"></i>Pesan otomatis akan disiapkan untuk Anda
                    </small>
                </div>
            </div>
            @endif

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
