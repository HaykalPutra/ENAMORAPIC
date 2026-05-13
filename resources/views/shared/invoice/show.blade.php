<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Invoice #{{ $invoice['invoice_number'] }}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#eef2f8; font-family: Inter, sans-serif; color:#1f2d47; }
.wrap { max-width:900px; margin:28px auto; }
.invoice-card {
    background:#fff;
    border:1px solid #e4ebf7;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 14px 34px rgba(11,23,54,.1);
}
.header {
    background:linear-gradient(120deg,#0b1736 0%,#1a2f6a 100%);
    color:#fff;
    padding:24px 28px;
}
.meta-chip {
    display:inline-block;
    border:1px solid rgba(255,255,255,.32);
    border-radius:999px;
    padding:4px 10px;
    font-size:.74rem;
    margin-top:8px;
}
.section { padding:20px 28px; border-bottom:1px solid #edf2fb; }
.section:last-child { border-bottom:none; }
.key { font-size:.73rem; color:#7688a8; text-transform:uppercase; letter-spacing:.08em; font-weight:700; }
.val { font-size:1rem; font-weight:700; color:#16233f; }
.total-box {
    border:1px solid #e2ebf8;
    border-radius:14px;
    padding:14px 16px;
    margin-bottom:10px;
    background:#fbfdff;
}
.total-title { color:#607596; font-size:.8rem; font-weight:600; }
.total-value { font-size:1.2rem; font-weight:800; color:#132444; }
.action-row { display:flex; gap:10px; flex-wrap:wrap; }
@media (max-width: 768px) {
    .wrap { margin:16px; }
    .header, .section { padding:16px; }
}
</style>
</head>
<body>
<div class="wrap">
    <div class="action-row mb-3">
        <a href="{{ $backRoute }}" class="btn btn-outline-secondary">Kembali ke Laporan</a>
        <a href="{{ $pdfRoute }}" target="_blank" class="btn btn-primary">Export Invoice PDF</a>
    </div>

    <div class="invoice-card">
        <div class="header d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h4 class="mb-1">Invoice Booking</h4>
                <div style="opacity:.85;">Enamorapic - {{ $roleLabel }}</div>
                <span class="meta-chip">{{ $invoice['invoice_number'] }}</span>
            </div>
            <div class="text-end">
                <div style="font-size:.78rem;opacity:.8;">Tanggal terbit</div>
                <div style="font-weight:700;">{{ $invoice['issued_at']->translatedFormat('d M Y H:i') }}</div>
            </div>
        </div>

        <div class="section">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="key">Nama Client</div>
                    <div class="val">{{ $invoice['customer_name'] }}</div>
                </div>
                <div class="col-md-6">
                    <div class="key">Paket</div>
                    <div class="val">{{ $invoice['paket'] }}</div>
                </div>
                <div class="col-md-6">
                    <div class="key">Tanggal Acara</div>
                    <div class="val">{{ optional($invoice['event_date'])->translatedFormat('d M Y') ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="key">Status Pembayaran</div>
                    <div class="val">{{ $invoice['status_pembayaran'] }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="total-box">
                <div class="total-title">Total Booking</div>
                <div class="total-value">Rp {{ number_format($invoice['total_transaksi'],0,',','.') }}</div>
            </div>
            <div class="total-box">
                <div class="total-title">Nominal Masuk</div>
                <div class="total-value" style="color:#1f8a4c;">Rp {{ number_format($invoice['nominal_masuk'],0,',','.') }}</div>
            </div>
            <div class="total-box mb-0">
                <div class="total-title">Sisa Tagihan</div>
                <div class="total-value" style="color:#ab5b05;">Rp {{ number_format($invoice['sisa_tagihan'],0,',','.') }}</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
