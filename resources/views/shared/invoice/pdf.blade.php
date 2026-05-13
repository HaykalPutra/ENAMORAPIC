<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice['invoice_number'] }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2d47; }
        .box { border: 1px solid #d8e2f2; border-radius: 8px; padding: 14px; margin-bottom: 10px; }
        .head {
            background: #0b1736;
            color: #fff;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .title { font-size: 18px; font-weight: 700; margin: 0 0 4px 0; }
        .sub { font-size: 11px; opacity: .85; }
        .grid { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .grid td { padding: 6px 0; }
        .k { width: 34%; color: #6b7c98; font-size: 10px; text-transform: uppercase; }
        .v { font-weight: 700; }
        .money { font-size: 16px; font-weight: 800; }
        .green { color: #1f8a4c; }
        .amber { color: #ab5b05; }
    </style>
</head>
<body>
    <div class="head">
        <div class="title">Invoice Booking</div>
        <div class="sub">Enamorapic - {{ $roleLabel }}</div>
        <div class="sub">No: {{ $invoice['invoice_number'] }} | Tanggal: {{ $invoice['issued_at']->translatedFormat('d M Y H:i') }}</div>
    </div>

    <div class="box">
        <table class="grid">
            <tr><td class="k">Nama Client</td><td class="v">{{ $invoice['customer_name'] }}</td></tr>
            <tr><td class="k">Paket</td><td class="v">{{ $invoice['paket'] }}</td></tr>
            <tr><td class="k">Tanggal Acara</td><td class="v">{{ optional($invoice['event_date'])->translatedFormat('d M Y') ?? '-' }}</td></tr>
            <tr><td class="k">Status Pembayaran</td><td class="v">{{ $invoice['status_pembayaran'] }}</td></tr>
        </table>
    </div>

    <div class="box">
        <div style="margin-bottom:10px;">
            <div style="font-size:10px;color:#6b7c98;">TOTAL BOOKING</div>
            <div class="money">Rp {{ number_format($invoice['total_transaksi'],0,',','.') }}</div>
        </div>
        <div style="margin-bottom:10px;">
            <div style="font-size:10px;color:#6b7c98;">NOMINAL MASUK</div>
            <div class="money green">Rp {{ number_format($invoice['nominal_masuk'],0,',','.') }}</div>
        </div>
        <div>
            <div style="font-size:10px;color:#6b7c98;">SISA TAGIHAN</div>
            <div class="money amber">Rp {{ number_format($invoice['sisa_tagihan'],0,',','.') }}</div>
        </div>
    </div>
</body>
</html>
