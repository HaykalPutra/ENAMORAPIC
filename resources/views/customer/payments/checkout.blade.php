<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Checkout Midtrans — Enamorapic</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
:root { --primary-dark: #1a2332; }
body { font-family: 'Inter', sans-serif; background: #f4f6f9; min-height: 100vh; }
h1,h2,h3,h4,h5,.serif { font-family: 'Playfair Display', serif; }
.topbar { background: var(--primary-dark); padding: 18px 0; }
.card-checkout { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
.btn-confirm { background: #22c55e; color: #fff; border-radius: 50px; padding: 14px 40px; font-weight: 600; font-size: 1.05rem; transition: all .35s; border: none; }
.btn-confirm:hover { background: #16a34a; color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(34,197,94,.3); }
.btn-back { background: #f1f5f9; color: #64748b; border-radius: 50px; padding: 12px 30px; border: none; transition: all .3s; }
.btn-back:hover { background: #e2e8f0; color: #334155; }
.amount-display { font-size: 2rem; font-weight: 700; color: var(--primary-dark); }
.info-row { padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
.info-row:last-child { border-bottom: none; }
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
        <div class="col-lg-6">

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

            <div class="card card-checkout">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-credit-card text-primary fs-3"></i>
                        </div>
                        <h4 class="serif fw-bold">Checkout Midtrans</h4>
                        <p class="text-muted small">Klik tombol bayar untuk membuka popup pembayaran Midtrans</p>
                    </div>

                    <div class="text-center mb-4">
                        <small class="text-muted d-block">Jumlah Pembayaran</small>
                        <div class="amount-display">{{ $amountFormatted }}</div>
                        <small class="text-muted">Termin {{ $term->term_number }} ({{ (int)$term->term_percentage }}%)</small>
                    </div>

                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="info-row d-flex justify-content-between small">
                            <span class="text-muted">Booking ID</span>
                            <strong>#{{ $booking->booking_id }}</strong>
                        </div>
                        <div class="info-row d-flex justify-content-between small">
                            <span class="text-muted">Nama</span>
                            <strong>{{ $booking->nama_client }}</strong>
                        </div>
                        <div class="info-row d-flex justify-content-between small">
                            <span class="text-muted">Paket</span>
                            <strong>{{ optional($booking->details->first())->paket->nama_paket ?? '-' }}</strong>
                        </div>
                        <div class="info-row d-flex justify-content-between small">
                            <span class="text-muted">Transaction ID</span>
                            <strong class="text-break" style="font-size: 0.75rem;">{{ $paymentLog->transaction_id }}</strong>
                        </div>
                        <div class="info-row d-flex justify-content-between small">
                            <span class="text-muted">Metode</span>
                            <strong>{{ strtoupper($paymentLog->payment_method) }}</strong>
                        </div>
                    </div>

                    <div class="bg-info bg-opacity-10 border border-info rounded-3 p-3 mb-4">
                        <small class="text-info fw-bold"><i class="bi bi-info-circle me-1"></i> Status transaksi: {{ strtoupper($paymentLog->payment_status) }}</small>
                        <p class="small text-muted mb-0 mt-1">Jika pembayaran berhasil, halaman ini otomatis mengecek status lalu mengarahkan kembali ke detail pembayaran.</p>
                    </div>

                    <button type="button" id="pay-button" class="btn btn-confirm w-100 mb-3">
                        <i class="bi bi-shield-check me-2"></i>Bayar Sekarang via Midtrans
                    </button>

                    <button type="button" id="check-status-button" class="btn btn-outline-primary w-100 rounded-pill mb-3">
                        <i class="bi bi-arrow-repeat me-2"></i>Cek Status Pembayaran
                    </button>

                    <a href="{{ route('payment.show', $booking->booking_id) }}" class="btn btn-back w-100">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Detail Pembayaran
                    </a>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none small">
                    <i class="bi bi-house me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script
    src="{{ $isMidtransProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ $midtransClientKey }}"></script>
<script>
    const transactionId = @json($paymentLog->transaction_id);
    const bookingId = @json($booking->booking_id);
    const snapToken = @json($snapToken);

    const verifyPayment = async () => {
        try {
            const url = `/payment/verify/${bookingId}?transaction_id=${encodeURIComponent(transactionId)}`;
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const payload = await response.json();
            if (payload.success && payload.status === 'paid') {
                window.location.href = `/payment/show/${bookingId}?paid=1`;
                return;
            }

            alert(payload.message || 'Pembayaran belum terkonfirmasi.');
        } catch (error) {
            alert('Gagal mengecek status pembayaran. Silakan coba lagi.');
        }
    };

    document.getElementById('pay-button').addEventListener('click', function () {
        window.snap.pay(snapToken, {
            onSuccess: function () {
                verifyPayment();
            },
            onPending: function () {
                alert('Transaksi masih pending. Silakan selesaikan pembayaran Anda.');
            },
            onError: function () {
                alert('Terjadi error saat proses pembayaran Midtrans.');
            },
            onClose: function () {
                // User menutup popup sebelum menyelesaikan pembayaran
            }
        });
    });

    document.getElementById('check-status-button').addEventListener('click', verifyPayment);
</script>
</body>
</html>
