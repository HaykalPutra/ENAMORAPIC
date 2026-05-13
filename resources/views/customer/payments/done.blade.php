<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pembayaran Lunas — Enamorapic</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
:root { --primary-dark: #1a2332; }
body { font-family: 'Inter', sans-serif; background: #f4f6f9; min-height: 100vh; }
h1,h2,h3,h4,h5,.serif { font-family: 'Playfair Display', serif; }
.topbar { background: var(--primary-dark); padding: 18px 0; }
.card-done { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
.check-circle { width: 80px; height: 80px; background: #22c55e; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; animation: scaleIn .5s ease; }
@keyframes scaleIn { from { transform: scale(0); } to { transform: scale(1); } }
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
            <div class="card card-done">
                <div class="card-body p-5 text-center">
                    <div class="check-circle mb-4">
                        <i class="bi bi-check-lg text-white fs-1"></i>
                    </div>
                    <h3 class="serif fw-bold mb-2">Pembayaran Lunas!</h3>
                    <p class="text-muted mb-4">Semua termin untuk Booking #{{ $booking->booking_id }} sudah terbayar.</p>
                    <div class="bg-light rounded-3 p-3 mb-4 text-start">
                        <div class="d-flex justify-content-between small py-1">
                            <span class="text-muted">Nama</span>
                            <strong>{{ $booking->nama_client }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small py-1">
                            <span class="text-muted">Total</span>
                            <strong>Rp {{ number_format((float)$booking->total_transaksi, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    <a href="{{ route('home') }}" class="btn btn-dark rounded-pill px-5 py-2">
                        <i class="bi bi-house me-2"></i>Kembali ke Beranda
                    </a>

                    @if(!empty($waMessage))
                    <div class="mt-4 pt-4 border-top">
                        <p class="text-muted small mb-3">Konfirmasi pembayaran Anda ke admin via WhatsApp:</p>
                        <a href="https://wa.me/{{ $adminPhone }}?text={{ urlencode($waMessage) }}"
                           target="_blank"
                           class="btn rounded-pill px-5 py-2 w-100"
                           style="background: #25d366; color: white; font-weight: 600;">
                            <i class="bi bi-whatsapp me-2"></i>Kirim Konfirmasi WhatsApp
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
