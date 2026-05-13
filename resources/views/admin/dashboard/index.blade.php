@extends('layouts.admin')
@section('title', 'Dashboard CEO')
@section('hideTopbarTitle', '1')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
/* ── ANIMATIONS ─────────────────────────────────────────── */
@keyframes slideUp   { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }
@keyframes slideLeft { from{opacity:0;transform:translateX(18px)} to{opacity:1;transform:translateX(0)} }
@keyframes countUp   { from{opacity:0;transform:translateY(8px) scale(.94)} to{opacity:1;transform:translateY(0) scale(1)} }
@keyframes shimmer   { 0%{background-position:-400px 0} 100%{background-position:400px 0} }
@keyframes pulse     { 0%,100%{opacity:1} 50%{opacity:.5} }

.anim-s1 { animation:slideUp .5s cubic-bezier(.22,1,.36,1) .05s both }
.anim-s2 { animation:slideUp .5s cubic-bezier(.22,1,.36,1) .12s both }
.anim-s3 { animation:slideUp .5s cubic-bezier(.22,1,.36,1) .19s both }
.anim-s4 { animation:slideUp .5s cubic-bezier(.22,1,.36,1) .26s both }
.anim-s5 { animation:slideUp .5s cubic-bezier(.22,1,.36,1) .33s both }
.anim-s6 { animation:slideUp .5s cubic-bezier(.22,1,.36,1) .40s both }
.anim-s7 { animation:slideUp .5s cubic-bezier(.22,1,.36,1) .47s both }
.anim-sl { animation:slideLeft .5s cubic-bezier(.22,1,.36,1) .32s both }

/* ── GREETING HERO ────────────────────────────────────────── */
.greeting-hero {
    background: linear-gradient(135deg,#0B1736 0%,#1A2F6A 60%,#0B1736 100%);
    border-radius: 20px;
    padding: 28px 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.greeting-hero::before {
    content:'';
    position:absolute;
    top:-60px;right:-60px;
    width:220px;height:220px;
    border-radius:50%;
    background:radial-gradient(circle,rgba(212,169,106,.22) 0%,transparent 70%);
}
.greeting-hero::after {
    content:'';
    position:absolute;
    bottom:-40px;left:200px;
    width:160px;height:160px;
    border-radius:50%;
    background:radial-gradient(circle,rgba(255,255,255,.04) 0%,transparent 70%);
}

/* ── STAT CARDS ───────────────────────────────────────────── */
.kpi-card {
    background:#fff;
    border-radius:16px;
    border:1px solid #E8EDF5;
    padding:22px 20px 18px;
    height:100%;
    transition:transform .25s ease, box-shadow .25s ease;
    position:relative;
    overflow:hidden;
    cursor:default;
}

.kpi-link,
.hero-mini-link,
.panel-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.kpi-link .kpi-card,
.hero-mini-link > div {
    cursor: pointer;
}
.kpi-card::after {
    content:'';
    position:absolute;
    bottom:0;left:0;right:0;
    height:3px;
    border-radius:0 0 16px 16px;
    background:var(--kc,#E8EDF5);
    transition:height .22s;
}
.kpi-card:hover { transform:translateY(-4px); box-shadow:0 10px 32px rgba(11,23,54,.12); }
.kpi-card:hover::after { height:4px; }

.kpi-icon {
    width:46px;height:46px;
    border-radius:13px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.2rem;
    margin-bottom:16px;
}
.kpi-num {
    font-size:1.9rem;
    font-weight:800;
    color:#0B1736;
    line-height:1;
    letter-spacing:-.03em;
    animation:countUp .6s ease both;
}
.kpi-label {
    font-size:.75rem;
    color:#94A3B8;
    font-weight:500;
    margin-top:5px;
    text-transform:uppercase;
    letter-spacing:.06em;
}
.kpi-trend {
    display:inline-flex;
    align-items:center;
    gap:4px;
    font-size:.72rem;
    font-weight:600;
    padding:3px 8px;
    border-radius:999px;
    margin-top:8px;
}

/* ── PANEL CARDS ──────────────────────────────────────────── */
.panel {
    background:#fff;
    border-radius:16px;
    border:1px solid #E8EDF5;
    box-shadow:0 2px 16px rgba(11,23,54,.06);
    height:100%;
}
.panel-hd {
    padding:18px 20px 14px;
    border-bottom:1px solid #F1F5FB;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.panel-title {
    font-size:.95rem;
    font-weight:700;
    color:#0B1736;
}
.panel-sub {
    font-size:.72rem;
    color:#94A3B8;
}
.panel-body { padding:18px 20px; }

/* ── BOOKING LIST ─────────────────────────────────────────── */
.booking-row {
    display:flex;
    align-items:center;
    gap:12px;
    padding:11px 0;
    border-bottom:1px solid #F4F7FC;
    transition:background .15s;
    border-radius:8px;
}
.booking-row:last-child { border-bottom:none; }
.booking-row:hover { background:#F8FAFD; padding-left:6px; }
.booking-avatar {
    width:36px;height:36px;
    border-radius:10px;
    display:flex;align-items:center;justify-content:center;
    font-size:.75rem;font-weight:700;
    flex-shrink:0;
}

/* ── PAKET RANK ───────────────────────────────────────────── */
.paket-row {
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:14px;
}
.paket-bar-wrap {
    flex:1;
    height:6px;
    background:#F0F3F9;
    border-radius:999px;
    overflow:hidden;
}
.paket-bar {
    height:100%;
    border-radius:999px;
    transition:width 1.2s cubic-bezier(.4,0,.2,1);
}

/* ── TABLE ────────────────────────────────────────────────── */
.tbl th {
    font-size:.7rem;
    text-transform:uppercase;
    letter-spacing:.07em;
    color:#94A3B8;
    font-weight:600;
    padding:10px 14px;
    background:#FAFBFD;
    border-bottom:1.5px solid #E8EDF5;
}
.tbl td {
    padding:12px 14px;
    font-size:.855rem;
    vertical-align:middle;
    border-bottom:1px solid #F1F5FB;
    color:#334155;
}
.tbl tbody tr:last-child td { border-bottom:none; }
.tbl tbody tr { transition:background .14s; }
.tbl tbody tr:hover td { background:#F7F9FD; }

/* ── STATUS BADGES ────────────────────────────────────────── */
.bs-lunas   { background:#DCFCE7;color:#16A34A;border-radius:6px;padding:3px 10px;font-size:.7rem;font-weight:700; }
.bs-dp      { background:#DBEAFE;color:#1D4ED8;border-radius:6px;padding:3px 10px;font-size:.7rem;font-weight:700; }
.bs-selesai { background:#DCFCE7;color:#16A34A;border-radius:6px;padding:3px 10px;font-size:.7rem;font-weight:700; }
.bs-pending { background:#FEF9C3;color:#A16207;border-radius:6px;padding:3px 10px;font-size:.7rem;font-weight:700; }
.bs-booked  { background:#EDE9FE;color:#6D28D9;border-radius:6px;padding:3px 10px;font-size:.7rem;font-weight:700; }
.bs-batal   { background:#FEE2E2;color:#B91C1C;border-radius:6px;padding:3px 10px;font-size:.7rem;font-weight:700; }

/* ── MINI CHART SPARK ─────────────────────────────────────── */
.spark-up { color:#16A34A }
.spark-dn { color:#DC2626 }

/* ── SCROLLBAR ────────────────────────────────────────────── */
.slim-scroll { scrollbar-width:thin; scrollbar-color:#E2E8F0 transparent; }
.slim-scroll::-webkit-scrollbar { width:4px; }
.slim-scroll::-webkit-scrollbar-thumb { background:#E2E8F0; border-radius:4px; }

/* ── DIVIDER ──────────────────────────────────────────────── */
.dot-live {
    width:8px;height:8px;border-radius:50%;
    background:#22C55E;
    box-shadow:0 0 0 3px rgba(34,197,94,.2);
    animation:pulse 2s ease infinite;
    display:inline-block;
}

.est-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.est-list li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #EEF3FA;
    font-size: .84rem;
}

.est-list li:last-child {
    border-bottom: none;
}
</style>
@endsection

@section('content')

{{-- ── GREETING HERO ──────────────────────────────────────────── --}}
<div class="greeting-hero anim-s1">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" style="position:relative;z-index:1">
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span class="dot-live"></span>
                <span style="font-size:.7rem;color:rgba(255,255,255,.5);letter-spacing:.14em;text-transform:uppercase">Live Dashboard</span>
            </div>
            <h2 style="color:#fff;font-size:1.55rem;font-weight:800;margin:0;letter-spacing:-.02em">
                Selamat datang, {{ Auth::user()->nama_lengkap }} 👋
            </h2>
            <p style="color:rgba(255,255,255,.55);font-size:.88rem;margin:5px 0 0">
                {{ now()->translatedFormat('l, d F Y') }} &nbsp;·&nbsp; Enamorapic Studio
            </p>
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('admin.pesanan') }}" class="hero-mini-link">
                <div style="background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.13);border-radius:14px;padding:14px 20px;min-width:120px;text-align:center;">
                    <div style="font-size:1.4rem;font-weight:800;color:#fff">{{ $totalBulanIni }}</div>
                    <div style="font-size:.7rem;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.1em;margin-top:2px">Booking bulan ini</div>
                </div>
            </a>
            <a href="{{ route('admin.laporan') }}" class="hero-mini-link">
                <div style="background:rgba(212,169,106,.18);border:1px solid rgba(212,169,106,.3);border-radius:14px;padding:14px 20px;min-width:120px;text-align:center;">
                    <div style="font-size:1.1rem;font-weight:800;color:#D4A96A">Rp {{ number_format($revenueBulanIni/1000000,1) }}jt</div>
                    <div style="font-size:.7rem;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.1em;margin-top:2px">Revenue DP + Lunas bulan ini</div>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- ── KPI CARDS ──────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3 anim-s2">
        <a href="{{ route('admin.pesanan') }}" class="kpi-link" title="Buka Data Pesanan">
            <div class="kpi-card" style="--kc:#2563EB">
                <div class="kpi-icon" style="background:#EFF6FF;color:#2563EB"><i class="bi bi-calendar2-check-fill"></i></div>
                <div class="kpi-num">{{ $totalBulanIni }}</div>
                <div class="kpi-label">Booking Bulan Ini</div>
                <div class="kpi-trend" style="background:#EFF6FF;color:#2563EB"><i class="bi bi-calendar3"></i> Buka Data Pesanan</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3 anim-s2" style="animation-delay:.07s">
        <a href="{{ route('admin.pesanan-website') }}" class="kpi-link" title="Buka Pesanan Website">
            <div class="kpi-card" style="--kc:#F59E0B">
                <div class="kpi-icon" style="background:#FFFBEB;color:#D97706"><i class="bi bi-hourglass-split"></i></div>
                <div class="kpi-num" style="color:#D97706">{{ $totalPending }}</div>
                <div class="kpi-label">Menunggu Konfirmasi</div>
                <div class="kpi-trend" style="background:#FEF3C7;color:#D97706"><i class="bi bi-clock"></i> Buka Pesanan Website</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3 anim-s2" style="animation-delay:.14s">
        <a href="{{ route('admin.laporan') }}" class="kpi-link" title="Buka Laporan">
            <div class="kpi-card" style="--kc:#10B981">
                <div class="kpi-icon" style="background:#ECFDF5;color:#10B981"><i class="bi bi-wallet2"></i></div>
                <div class="kpi-num" style="font-size:1.4rem;color:#10B981">Rp {{ number_format($revenueBulanIni/1000000,1) }}jt</div>
                <div class="kpi-label">Revenue DP + Lunas Bulan Ini</div>
                <div class="kpi-trend" style="background:#DCFCE7;color:#16A34A"><i class="bi bi-arrow-up-short"></i> Buka Laporan</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3 anim-s2" style="animation-delay:.21s">
        <a href="{{ route('admin.pesanan') }}" class="kpi-link" title="Buka Data Pesanan">
            <div class="kpi-card" style="--kc:#8B5CF6">
                <div class="kpi-icon" style="background:#F5F3FF;color:#8B5CF6"><i class="bi bi-check2-circle"></i></div>
                <div class="kpi-num" style="color:#8B5CF6">{{ $totalSelesai }}</div>
                <div class="kpi-label">Total Selesai</div>
                <div class="kpi-trend" style="background:#EDE9FE;color:#7C3AED"><i class="bi bi-trophy"></i> Buka Data Pesanan</div>
            </div>
        </a>
    </div>
</div>

{{-- ── ESTIMASI PENDAPATAN BULAN INI ───────────────────────── --}}
<div class="row g-4 mb-4">
    <div class="col-lg-7 anim-s3" style="animation-delay:.06s">
        <div class="panel">
            <div class="panel-hd">
                <div>
                    <div class="panel-title"><i class="bi bi-cash-coin me-2" style="color:#D97706"></i>Estimasi Pendapatan Bulan Ini</div>
                    <div class="panel-sub">Simulasi jika semua booking bulan berjalan berubah menjadi lunas</div>
                </div>
                <a href="{{ route('admin.laporan') }}" style="font-size:.75rem;background:#FFFBEB;color:#B45309;border-radius:6px;padding:4px 10px;font-weight:600;text-decoration:none;">Lihat Laporan</a>
            </div>
            <div class="panel-body">
                <ul class="est-list">
                    <li>
                        <span>Total potensi jika semua lunas</span>
                        <strong>Rp {{ number_format($potensiPendapatanBulanIni, 0, ',', '.') }}</strong>
                    </li>
                    <li>
                        <span>Realisasi lunas saat ini</span>
                        <strong style="color:#16A34A">Rp {{ number_format($lunasMasukBulanIni, 0, ',', '.') }}</strong>
                    </li>
                    <li>
                        <span>Estimasi sisa yang bisa masuk</span>
                        <strong style="color:#D97706">Rp {{ number_format($estimasiSisaPendapatanBulanIni, 0, ',', '.') }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-5 anim-s3" style="animation-delay:.12s">
        <div class="panel">
            <div class="panel-hd">
                <div>
                    <div class="panel-title"><i class="bi bi-pie-chart me-2" style="color:#2563EB"></i>Chart Estimasi Bulan Ini</div>
                    <div class="panel-sub">Realisasi vs sisa potensi pendapatan</div>
                </div>
            </div>
            <div class="panel-body">
                <div style="position:relative;height:220px"><canvas id="estimasiChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

{{-- ── CHART REVENUE + BOOKING TERBARU ───────────────────────── --}}
<div class="row g-4 mb-4">

    {{-- Line chart --}}
    <div class="col-lg-8 anim-s3">
        <div class="panel">
            <div class="panel-hd">
                <div>
                    <div class="panel-title"><i class="bi bi-graph-up-arrow me-2" style="color:#2563EB"></i>Tren Revenue 6 Bulan Terakhir</div>
                    <div class="panel-sub">Perbandingan pendapatan lunas vs potensi jika semua lunas</div>
                </div>
                <a href="{{ route('admin.laporan') }}" style="font-size:.72rem;background:#EFF6FF;color:#2563EB;border-radius:6px;padding:4px 10px;font-weight:600;text-decoration:none;">Buka Laporan</a>
            </div>
            <div class="panel-body">
                <div style="position:relative;height:260px"><canvas id="revenueChart"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Booking terbaru --}}
    <div class="col-lg-4 anim-sl">
        <div class="panel">
            <div class="panel-hd">
                <div>
                    <div class="panel-title"><i class="bi bi-bell-fill me-2" style="color:#F59E0B"></i>Booking Terbaru</div>
                    <div class="panel-sub">10 booking paling baru</div>
                </div>
            </div>
            <div class="panel-body slim-scroll" style="max-height:296px;overflow-y:auto;padding-top:8px">
                @php
                    $avatarColors = [
                        ['bg'=>'#EFF6FF','color'=>'#2563EB'],['bg'=>'#F5F3FF','color'=>'#7C3AED'],
                        ['bg'=>'#ECFDF5','color'=>'#059669'],['bg'=>'#FFF7ED','color'=>'#EA580C'],
                        ['bg'=>'#FDF2F8','color'=>'#BE185D'],
                    ];
                @endphp
                @forelse($bookingTerbaru as $idx => $b)
                @php
                    $ac = $avatarColors[$idx % count($avatarColors)];
                    $initials = strtoupper(substr($b->nama_client ?? 'X', 0, 2));
                    $statusClass = match(strtolower($b->job_status ?? '')) {
                        'selesai' => 'bs-selesai',
                        'batal'   => 'bs-batal',
                        'booked'  => 'bs-booked',
                        'confirmed' => 'bs-booked',
                        'lunas'   => 'bs-lunas',
                        'dp'      => 'bs-dp',
                        default   => 'bs-pending',
                    };
                @endphp
                <a href="{{ route('admin.pesanan.show', $b->booking_id) }}" class="booking-row" style="text-decoration:none">
                    <div class="booking-avatar" style="background:{{ $ac['bg'] }};color:{{ $ac['color'] }}">{{ $initials }}</div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.83rem;font-weight:600;color:#0B1736;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $b->nama_client }}</div>
                        <div style="font-size:.7rem;color:#94A3B8;margin-top:1px">{{ $b->tgl_acara ? \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') : '-' }}</div>
                    </div>
                    <span class="{{ $statusClass }}">{{ $b->job_status }}</span>
                </a>
                @empty
                <div style="text-align:center;color:#94A3B8;padding:32px 0;font-size:.85rem">Belum ada booking</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── BAR CHART TAHUNAN ──────────────────────────────────────── --}}
<div class="row g-4 mb-4">
    <div class="col-12 anim-s4">
        <div class="panel">
            <div class="panel-hd">
                <div>
                    <div class="panel-title"><i class="bi bi-bar-chart-fill me-2" style="color:#10B981"></i>Performa Tahunan {{ now()->year }}</div>
                    <div class="panel-sub">Total pendapatan masuk per bulan (Jan – Des {{ now()->year }})</div>
                </div>
                <span style="font-size:.72rem;background:#ECFDF5;color:#10B981;border-radius:6px;padding:4px 10px;font-weight:600;">{{ now()->year }}</span>
            </div>
            <div class="panel-body">
                <div style="position:relative;height:300px"><canvas id="yearChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

{{-- ── DOUGHNUT + PAKET POPULER ────────────────────────────────── --}}
<div class="row g-4 mb-4">

    {{-- Doughnut status pembayaran --}}
    <div class="col-md-5 anim-s5">
        <div class="panel">
            <div class="panel-hd">
                <div>
                    <div class="panel-title"><i class="bi bi-pie-chart-fill me-2" style="color:#8B5CF6"></i>Status Pembayaran</div>
                    <div class="panel-sub">Distribusi Lunas vs DP</div>
                </div>
            </div>
            <div class="panel-body">
                <div style="position:relative;height:220px"><canvas id="paymentChart"></canvas></div>
                {{-- Summary bawah chart --}}
                <div class="row g-2 mt-3">
                    <div class="col-6">
                        <div style="background:#DCFCE7;border-radius:10px;padding:10px 14px;text-align:center">
                            <div style="font-size:1.3rem;font-weight:800;color:#16A34A">{{ $lunas }}</div>
                            <div style="font-size:.7rem;color:#16A34A;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Lunas</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:#DBEAFE;border-radius:10px;padding:10px 14px;text-align:center">
                            <div style="font-size:1.3rem;font-weight:800;color:#1D4ED8">{{ $dp }}</div>
                            <div style="font-size:.7rem;color:#1D4ED8;font-weight:600;text-transform:uppercase;letter-spacing:.06em">DP</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Paket populer --}}
    <div class="col-md-7 anim-s5" style="animation-delay:.07s">
        <div class="panel">
            <div class="panel-hd">
                <div>
                    <div class="panel-title"><i class="bi bi-trophy-fill me-2" style="color:#F59E0B"></i>Paket Terpopuler</div>
                    <div class="panel-sub">Berdasarkan jumlah booking</div>
                </div>
            </div>
            <div class="panel-body slim-scroll" style="max-height:320px;overflow-y:auto">
                @php
                    $barColors = ['#2563EB','#8B5CF6','#10B981','#F59E0B','#EF4444'];
                    $maxCount  = $paketPopuler->max('total') ?: 1;
                    $rankIcons = ['🥇','🥈','🥉','4️⃣','5️⃣'];
                @endphp
                @forelse($paketPopuler as $pi => $p)
                <div class="paket-row">
                    <div style="width:26px;font-size:.95rem;text-align:center;flex-shrink:0">{{ $rankIcons[$pi] ?? ($pi+1) }}</div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;color:#0B1736;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:5px">{{ $p->nama_paket }}</div>
                        <div class="paket-bar-wrap">
                            <div class="paket-bar" style="width:{{ round($p->total / $maxCount * 100) }}%;background:{{ $barColors[$pi % 5] }}"></div>
                        </div>
                    </div>
                    <div style="font-size:.82rem;font-weight:700;color:{{ $barColors[$pi % 5] }};min-width:52px;text-align:right">{{ $p->total }}x</div>
                </div>
                @empty
                <div style="text-align:center;color:#94A3B8;padding:24px 0;font-size:.85rem">Belum ada data paket</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── TABEL BOOKING TERBARU (LENGKAP) ──────────────────────── --}}
<div class="panel anim-s6" style="margin-bottom:32px">
    <div class="panel-hd">
        <div>
            <div class="panel-title"><i class="bi bi-table me-2" style="color:#0B1736"></i>Semua Booking Terbaru</div>
            <div class="panel-sub">10 booking terakhir masuk</div>
        </div>
        <a href="{{ route('admin.pesanan') }}" style="font-size:.78rem;font-weight:600;color:#2563EB;text-decoration:none">Lihat semua →</a>
    </div>
    <div class="table-responsive">
        <table class="table tbl mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Client</th>
                    <th>Tanggal Acara</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status Acara</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookingTerbaru as $idx => $b)
                @php
                    $payClass  = $b->status_pembayaran === 'Lunas' ? 'bs-lunas' : 'bs-dp';
                    $statClass = match(strtolower($b->job_status ?? '')) {
                        'selesai'   => 'bs-selesai',
                        'batal'     => 'bs-batal',
                        'booked'    => 'bs-booked',
                        'confirmed' => 'bs-booked',
                        default     => 'bs-pending',
                    };
                @endphp
                <tr>
                    <td style="color:#94A3B8;font-size:.78rem">{{ $idx + 1 }}</td>
                    <td style="font-weight:600;color:#0B1736">
                        <a href="{{ route('admin.pesanan.show', $b->booking_id) }}" style="text-decoration:none;color:#0B1736">{{ $b->nama_client }}</a>
                    </td>
                    <td>{{ $b->tgl_acara ? \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') : '-' }}</td>
                    <td style="font-weight:600">Rp {{ number_format($b->total_transaksi ?? 0, 0, ',', '.') }}</td>
                    <td><span class="{{ $payClass }}">{{ $b->status_pembayaran }}</span></td>
                    <td><span class="{{ $statClass }}">{{ $b->job_status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:#94A3B8;padding:24px">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const months6    = @json($months6);
const lunas6     = @json($lunas6);
const potensi6   = @json($potensi6);
const yearlyData = @json($yearlyData);
const estimasiRealisasi = @json($lunasMasukBulanIni);
const estimasiSisa = @json($estimasiSisaPendapatanBulanIni);

/* ── Gradient helper ── */
function makeGrad(ctx, c1, c2) {
    const g = ctx.createLinearGradient(0,0,0,300);
    g.addColorStop(0, c1); g.addColorStop(1, c2); return g;
}

/* ── Line Chart Revenue 6 Bulan ── */
(function(){
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const grad = makeGrad(ctx,'rgba(37,99,235,.18)','rgba(37,99,235,.01)');
    new Chart(ctx,{
        type:'line',
        data:{
            labels:months6,
            datasets:[
                {
                    label:'Lunas Masuk',
                    data:lunas6,
                    borderColor:'#2563EB',
                    backgroundColor:grad,
                    tension:.42,
                    fill:true,
                    borderWidth:2.5,
                    pointRadius:5,
                    pointHoverRadius:7,
                    pointBackgroundColor:'#fff',
                    pointBorderColor:'#2563EB',
                    pointBorderWidth:2,
                },
                {
                    label:'Potensi Jika Semua Lunas',
                    data:potensi6,
                    borderColor:'#D97706',
                    backgroundColor:'transparent',
                    tension:.35,
                    fill:false,
                    borderWidth:2,
                    borderDash:[6,4],
                    pointRadius:3,
                    pointHoverRadius:5,
                    pointBackgroundColor:'#fff',
                    pointBorderColor:'#D97706',
                    pointBorderWidth:1.8,
                }
            ]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            interaction:{mode:'index',intersect:false},
            plugins:{
                legend:{display:true, labels:{usePointStyle:true, boxWidth:8}},
                tooltip:{
                    backgroundColor:'#0B1736',
                    titleColor:'rgba(255,255,255,.7)',
                    bodyColor:'#fff',
                    padding:12,
                    cornerRadius:10,
                    callbacks:{label:c=>'  Rp '+new Intl.NumberFormat('id-ID').format(c.parsed.y)}
                }
            },
            scales:{
                y:{
                    beginAtZero:true,
                    grid:{color:'#F0F3F9',drawBorder:false},
                    border:{display:false},
                    ticks:{callback:v=>(v/1000000).toFixed(0)+'jt',color:'#94A3B8',font:{size:11}}
                },
                x:{
                    grid:{display:false},
                    border:{display:false},
                    ticks:{color:'#94A3B8',font:{size:11}}
                }
            }
        }
    });
})();

/* ── Doughnut Estimasi Bulan Ini ── */
(function(){
    const ctx = document.getElementById('estimasiChart').getContext('2d');
    const emptyState = Number(estimasiRealisasi) === 0 && Number(estimasiSisa) === 0;

    new Chart(ctx,{
        type:'doughnut',
        data:{
            labels:['Realisasi Lunas', 'Estimasi Sisa'],
            datasets:[{
                data: emptyState ? [1, 0] : [estimasiRealisasi, estimasiSisa],
                backgroundColor:['#10B981','#F59E0B'],
                hoverBackgroundColor:['#059669','#D97706'],
                borderWidth:3,
                borderColor:'#fff',
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            cutout:'65%',
            plugins:{
                legend:{
                    position:'bottom',
                    labels:{usePointStyle:true, pointStyleWidth:10, font:{size:11}, color:'#334155'}
                },
                tooltip:{
                    backgroundColor:'#0B1736',
                    callbacks:{
                        label:c=>` ${c.label}: Rp ${new Intl.NumberFormat('id-ID').format(c.parsed)}`
                    }
                }
            }
        }
    });
})();

/* ── Bar Chart Tahunan ── */
(function(){
    const ctx = document.getElementById('yearChart').getContext('2d');
    const grad = makeGrad(ctx,'rgba(16,185,129,.85)','rgba(16,185,129,.4)');
    new Chart(ctx,{
        type:'bar',
        data:{
            labels:['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets:[{
                label:'Pendapatan {{ now()->year }}',
                data:yearlyData,
                backgroundColor: yearlyData.map((_,i) =>
                    i === new Date().getMonth()
                        ? 'rgba(37,99,235,.85)'
                        : 'rgba(16,185,129,.72)'
                ),
                borderRadius:8,
                borderSkipped:false,
                barPercentage:.6,
                categoryPercentage:.7,
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            interaction:{mode:'index',intersect:false},
            plugins:{
                legend:{display:false},
                tooltip:{
                    backgroundColor:'#0B1736',
                    padding:12,
                    cornerRadius:10,
                    titleColor:'rgba(255,255,255,.7)',
                    bodyColor:'#fff',
                    callbacks:{label:c=>'  Rp '+new Intl.NumberFormat('id-ID').format(c.parsed.y)}
                }
            },
            scales:{
                y:{
                    beginAtZero:true,
                    grid:{color:'#F0F3F9',drawBorder:false},
                    border:{display:false},
                    ticks:{callback:v=>(v/1000000).toFixed(0)+'jt',color:'#94A3B8',font:{size:11}}
                },
                x:{
                    grid:{display:false},
                    border:{display:false},
                    ticks:{color:'#94A3B8',font:{size:11}}
                }
            }
        }
    });
})();

/* ── Doughnut Status Pembayaran ── */
(function(){
    const ctx = document.getElementById('paymentChart').getContext('2d');
    new Chart(ctx,{
        type:'doughnut',
        data:{
            labels:['Lunas','DP'],
            datasets:[{
                data:[{{ $lunas }},{{ $dp }}],
                backgroundColor:['#10B981','#2563EB'],
                hoverBackgroundColor:['#059669','#1D4ED8'],
                borderWidth:3,
                borderColor:'#fff',
                hoverOffset:6,
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            cutout:'68%',
            plugins:{
                legend:{
                    position:'right',
                    labels:{
                        usePointStyle:true,
                        pointStyleWidth:10,
                        font:{size:12},
                        color:'#334155',
                        padding:18,
                    }
                },
                tooltip:{
                    backgroundColor:'#0B1736',
                    padding:12,
                    cornerRadius:10,
                    bodyColor:'#fff',
                }
            }
        }
    });
})();

/* ── Animate paket bars on load ── */
document.querySelectorAll('.paket-bar').forEach(el => {
    const w = el.style.width;
    el.style.width = '0';
    setTimeout(() => { el.style.width = w; }, 400);
});
</script>
@endsection