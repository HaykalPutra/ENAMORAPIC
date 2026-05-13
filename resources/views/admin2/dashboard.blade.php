@extends('layouts.admin2')
@section('title','Dashboard Sekretaris')
@section('hideTopbarTitle', '1')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ─── Base ─────────────────────────────────── */
.sk-wrap { font-family:'Plus Jakarta Sans',sans-serif; }

/* ─── Hero ──────────────────────────────────── */
.sk-hero {
    background: linear-gradient(118deg, #0b1736 0%, #112358 55%, #0d1d44 100%);
    border-radius: 20px;
    padding: 30px 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.sk-hero::before {
    content:'';
    position:absolute; right:-60px; top:-80px;
    width:320px; height:320px; border-radius:50%;
    background:radial-gradient(circle,rgba(99,149,255,.18) 0%,transparent 65%);
    pointer-events:none;
}
.sk-hero::after {
    content:'';
    position:absolute; left:220px; bottom:-80px;
    width:240px; height:240px; border-radius:50%;
    background:radial-gradient(circle,rgba(59,130,246,.1) 0%,transparent 70%);
    pointer-events:none;
}
.sk-hero > * { position:relative; z-index:1; }

.sk-hero-eyebrow {
    display:inline-flex; align-items:center; gap:7px;
    background:rgba(99,149,255,.15);
    border:1px solid rgba(99,149,255,.3);
    border-radius:999px;
    padding:5px 13px;
    font-size:.7rem; font-weight:700;
    color:#93b8ff;
    letter-spacing:.05em;
    text-transform:uppercase;
    margin-bottom:14px;
}
.sk-hero-eyebrow span.dot {
    width:6px; height:6px; border-radius:50%;
    background:#60a5fa;
    box-shadow:0 0 6px #60a5fa;
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%,100%{ opacity:1; transform:scale(1); }
    50%{ opacity:.6; transform:scale(.85); }
}
.sk-hero-title {
    font-size:1.65rem; font-weight:800;
    color:#fff; margin:0 0 6px;
    letter-spacing:-.4px; line-height:1.2;
}
.sk-hero-title span { color:#93b8ff; }
.sk-hero-desc {
    color:rgba(255,255,255,.52);
    font-size:.84rem; line-height:1.65;
    margin:0 0 22px; max-width:680px;
}
.sk-hero-actions { display:flex; gap:10px; flex-wrap:wrap; }
.sk-btn-primary {
    background:#3b82f6;
    color:#fff !important;
    font-weight:700; font-size:.82rem;
    padding:10px 22px; border-radius:11px; border:none;
    display:inline-flex; align-items:center; gap:8px;
    text-decoration:none;
    transition:background .2s, transform .15s, box-shadow .2s;
    box-shadow:0 4px 14px rgba(59,130,246,.35);
}
.sk-btn-primary:hover {
    background:#2563eb; transform:translateY(-1px);
    box-shadow:0 6px 18px rgba(59,130,246,.45);
    color:#fff !important;
}
.sk-btn-ghost {
    background:rgba(255,255,255,.08);
    color:rgba(255,255,255,.8) !important;
    font-weight:600; font-size:.82rem;
    padding:10px 22px; border-radius:11px;
    border:1px solid rgba(255,255,255,.15);
    display:inline-flex; align-items:center; gap:8px;
    text-decoration:none;
    transition:background .2s, border-color .2s;
}
.sk-btn-ghost:hover {
    background:rgba(255,255,255,.13);
    border-color:rgba(255,255,255,.3);
    color:#fff !important;
}

/* ─── Stat Cards ─────────────────────────────── */
.sk-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
@media(max-width:900px){ .sk-stats{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:500px){ .sk-stats{ grid-template-columns:1fr; } }

.sk-stat {
    background:#fff;
    border:1px solid #e8eef8;
    border-radius:16px;
    padding:20px 22px;
    display:block; text-decoration:none;
    transition:transform .2s, box-shadow .2s, border-color .2s;
    position:relative; overflow:hidden;
}
.sk-stat::before {
    content:'';
    position:absolute; right:-20px; bottom:-20px;
    width:80px; height:80px; border-radius:50%;
    opacity:.07;
    transition:opacity .2s;
}
.sk-stat:hover {
    transform:translateY(-3px);
    box-shadow:0 12px 32px rgba(11,23,54,.1);
    text-decoration:none;
}
.sk-stat:hover::before { opacity:.13; }
.sk-stat:hover { border-color:#c7d9f8; }

.sk-stat-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; }
.sk-stat-icon {
    width:42px; height:42px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:.95rem; flex-shrink:0;
}
.sk-stat-trend {
    font-size:.7rem; font-weight:700;
    border-radius:7px; padding:3px 9px;
    display:inline-flex; align-items:center; gap:4px;
}
.sk-stat-label {
    font-size:.68rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.08em;
    color:#8a9ab8; margin-bottom:5px;
}
.sk-stat-value {
    font-size:2rem; font-weight:800;
    line-height:1; margin-bottom:4px;
    font-family:'Plus Jakarta Sans',sans-serif;
}
.sk-stat-note { font-size:.76rem; color:#8a9ab8; }

/* Color variants */
.sk-stat-blue .sk-stat-icon { background:#eff5ff; color:#3b82f6; }
.sk-stat-blue::before { background:#3b82f6; }
.sk-stat-blue .sk-stat-value { color:#1d3a7a; }
.sk-stat-blue:hover { box-shadow:0 12px 32px rgba(59,130,246,.12); }

.sk-stat-indigo .sk-stat-icon { background:#eef0ff; color:#6366f1; }
.sk-stat-indigo::before { background:#6366f1; }
.sk-stat-indigo .sk-stat-value { color:#312e81; }
.sk-stat-indigo:hover { box-shadow:0 12px 32px rgba(99,102,241,.12); }

.sk-stat-amber .sk-stat-icon { background:#fffbeb; color:#f59e0b; }
.sk-stat-amber::before { background:#f59e0b; }
.sk-stat-amber .sk-stat-value { color:#92400e; }
.sk-stat-amber:hover { box-shadow:0 12px 32px rgba(245,158,11,.12); }

.sk-stat-emerald .sk-stat-icon { background:#ecfdf5; color:#10b981; }
.sk-stat-emerald::before { background:#10b981; }
.sk-stat-emerald .sk-stat-value { color:#064e3b; }
.sk-stat-emerald:hover { box-shadow:0 12px 32px rgba(16,185,129,.12); }

/* ─── Panel ───────────────────────────────────── */
.sk-panel {
    background:#fff;
    border:1px solid #e8eef8;
    border-radius:16px;
    overflow:hidden;
}
.sk-panel-hd {
    padding:16px 22px 14px;
    border-bottom:1px solid #f0f5fd;
    display:flex; align-items:flex-start; justify-content:space-between;
}
.sk-panel-title {
    font-size:.92rem; font-weight:800;
    color:#0f1f42; margin:0 0 3px;
}
.sk-panel-sub { font-size:.74rem; color:#8a9ab8; margin:0; }
.sk-panel-badge {
    font-size:.7rem; font-weight:700;
    border-radius:8px; padding:4px 10px;
    flex-shrink:0; margin-top:2px;
}

/* ─── Calendar ────────────────────────────────── */
.sk-cal { padding:16px 18px; }
.sk-cal-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; }
.sk-cal-head {
    text-align:center; font-size:.63rem; font-weight:700;
    letter-spacing:.08em; text-transform:uppercase;
    color:#8a9ab8; padding:8px 2px;
}
.sk-cal-cell { text-align:center; padding:2px; }
.sk-cal-day {
    display:inline-flex; flex-direction:column;
    align-items:center; justify-content:center;
    width:100%; min-width:30px;
    border-radius:10px; padding:7px 2px 5px;
    font-size:.8rem; font-weight:700; color:#1a2a4a;
    text-decoration:none; transition:all .15s;
    cursor:default;
}
.sk-cal-day.other { color:#c5d0e0; font-weight:500; }
.sk-cal-day.has-event {
    background:linear-gradient(135deg,#eff6ff,#dbeafe);
    border:1.5px solid #93c5fd;
    color:#1e40af;
    cursor:pointer;
    box-shadow:0 2px 8px rgba(59,130,246,.12);
}
.sk-cal-day.has-event:hover {
    background:linear-gradient(135deg,#dbeafe,#bfdbfe);
    transform:scale(1.08);
    box-shadow:0 4px 14px rgba(59,130,246,.2);
}
.sk-cal-count {
    font-size:.58rem; font-weight:800;
    color:#3b82f6; margin-top:2px;
    line-height:1;
}

/* ─── DP Table ────────────────────────────────── */
.sk-dp-table { width:100%; border-collapse:collapse; font-size:.8rem; }
.sk-dp-table thead th {
    background:#f7faff;
    color:#8a9ab8; font-size:.64rem; font-weight:700;
    letter-spacing:.08em; text-transform:uppercase;
    padding:10px 16px; border-bottom:1px solid #e8eef8;
    white-space:nowrap;
}
.sk-dp-table tbody td {
    padding:11px 16px; border-bottom:1px solid #f3f7fd;
    vertical-align:middle;
}
.sk-dp-table tbody tr:last-child td { border-bottom:none; }
.sk-dp-table tbody tr { transition:background .15s; cursor:pointer; }
.sk-dp-table tbody tr:hover td { background:#f7faff; }

.sk-termin {
    display:inline-flex; align-items:center; gap:5px;
    background:#fffbeb; border:1px solid #fde68a;
    color:#92400e; border-radius:8px;
    font-size:.69rem; font-weight:700; padding:4px 10px;
}
.sk-termin-bar {
    height:4px; border-radius:999px; background:#fde68a; overflow:hidden; width:36px;
}
.sk-termin-fill { height:100%; border-radius:999px; background:#f59e0b; }

/* ─── Booking Table ───────────────────────────── */
.sk-book-table { width:100%; border-collapse:collapse; font-size:.8rem; }
.sk-book-table thead th {
    background:#0b1736;
    color:rgba(255,255,255,.42); font-size:.63rem; font-weight:700;
    letter-spacing:.09em; text-transform:uppercase;
    padding:13px 18px; border:none; white-space:nowrap;
}
.sk-book-table thead th:first-child { border-radius:0; }
.sk-book-table tbody td {
    padding:13px 18px; border-bottom:1px solid #f0f5fd; vertical-align:middle;
}
.sk-book-table tbody tr:last-child td { border-bottom:none; }
.sk-book-table tbody tr { transition:background .15s; cursor:pointer; }
.sk-book-table tbody tr:hover td { background:#f7faff; }

.sk-client-name { font-weight:700; color:#0f1f42; font-size:.85rem; }
.sk-pkg { color:#8a9ab8; font-size:.77rem; }
.sk-date { color:#5a6b8a; font-size:.8rem; }

.sk-badge {
    display:inline-block; border-radius:8px;
    font-size:.69rem; font-weight:700;
    padding:4px 11px; letter-spacing:.3px;
}
.sk-badge-ok  { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
.sk-badge-warn{ background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
.sk-badge-blue{ background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; }
.sk-badge-red { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

.sk-amount { font-weight:800; color:#0f1f42; font-size:.84rem; }

/* ─── Responsive ──────────────────────────────── */
@media(max-width:768px){
    .sk-hero { padding:22px 20px; }
    .sk-hero-title { font-size:1.3rem; }
    .sk-row2 { grid-template-columns:1fr !important; }
}
</style>
@endsection

@section('content')
<div class="sk-wrap">

{{-- ── Hero ─────────────────────────────────────────── --}}
<div class="sk-hero">
    <div class="sk-hero-eyebrow">
        <span class="dot"></span>
        Sekretaris Panel
    </div>
    <h2 class="sk-hero-title">
        Halo, <span>{{ Auth::user()->nama_lengkap }}!</span>
    </h2>
    <p class="sk-hero-desc">
        Ringkas operasional harian Sekretaris — pantau tim, cek pesanan masuk, dan kendalikan progres booking dalam satu tempat.
    </p>
    <div class="sk-hero-actions">
        <a href="{{ route('admin2.freelance.index') }}" class="sk-btn-primary">
            <i class="bi bi-camera2" style="font-size:.9rem;"></i>
            Kelola Freelance
        </a>
        <a href="{{ route('admin2.pesanan') }}" class="sk-btn-ghost">
            <i class="bi bi-clipboard-check" style="font-size:.9rem;"></i>
            Cek Pesanan
        </a>
    </div>
</div>

{{-- ── Stat Cards ───────────────────────────────────── --}}
<div class="sk-stats">
    <a href="{{ route('admin2.freelance.index') }}" class="sk-stat sk-stat-blue">
        <div class="sk-stat-top">
            <div class="sk-stat-icon"><i class="bi bi-camera2"></i></div>
            <span class="sk-stat-trend" style="background:#eff6ff;color:#3b82f6;">
                <i class="bi bi-people-fill" style="font-size:.65rem;"></i> Tim
            </span>
        </div>
        <div class="sk-stat-label">Freelance</div>
        <div class="sk-stat-value">{{ $totalFreelance }}</div>
        <div class="sk-stat-note">Fotografer &amp; videografer terdaftar</div>
    </a>

    <a href="{{ route('admin2.pegawai.index') }}" class="sk-stat sk-stat-indigo">
        <div class="sk-stat-top">
            <div class="sk-stat-icon"><i class="bi bi-person-vcard"></i></div>
            <span class="sk-stat-trend" style="background:#eef0ff;color:#6366f1;">
                <i class="bi bi-building" style="font-size:.65rem;"></i> Internal
            </span>
        </div>
        <div class="sk-stat-label">Pegawai</div>
        <div class="sk-stat-value">{{ $totalPegawai }}</div>
        <div class="sk-stat-note">Staff internal aktif</div>
    </a>

    <a href="{{ route('admin2.pesanan') }}" class="sk-stat sk-stat-amber">
        <div class="sk-stat-top">
            <div class="sk-stat-icon"><i class="bi bi-bell-fill"></i></div>
            <span class="sk-stat-trend" style="background:#fffbeb;color:#f59e0b;">
                <i class="bi bi-clock" style="font-size:.65rem;"></i> Urgent
            </span>
        </div>
        <div class="sk-stat-label">Pesanan Pending</div>
        <div class="sk-stat-value">{{ $pesananPending }}</div>
        <div class="sk-stat-note">Perlu segera diproses</div>
    </a>

    <a href="{{ route('admin2.laporan', ['bulan' => now()->month, 'tahun' => now()->year]) }}" class="sk-stat sk-stat-emerald">
        <div class="sk-stat-top">
            <div class="sk-stat-icon"><i class="bi bi-calendar-check"></i></div>
            <span class="sk-stat-trend" style="background:#ecfdf5;color:#10b981;">
                <i class="bi bi-graph-up" style="font-size:.65rem;"></i> Bulan ini
            </span>
        </div>
        <div class="sk-stat-label">Booking</div>
        <div class="sk-stat-value">{{ $bookingBulanIni }}</div>
        <div class="sk-stat-note">Total booking bulan ini</div>
    </a>
</div>

{{-- ── Calendar + DP Follow Up ──────────────────────── --}}
<div class="sk-row2" style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:18px;">

    {{-- Calendar --}}
    <div class="sk-panel">
        <div class="sk-panel-hd">
            <div>
                <div class="sk-panel-title">
                    <i class="bi bi-calendar3" style="color:#3b82f6;margin-right:7px;font-size:.85rem;"></i>
                    Kalender {{ $currentMonth->locale('id')->isoFormat('MMMM Y') }}
                </div>
                <p class="sk-panel-sub">Hari dengan indikator wedding / prewed.</p>
            </div>
        </div>
        <div class="sk-cal">
            <div class="sk-cal-grid">
                @foreach(['MIN','SEN','SEL','RAB','KAM','JUM','SAB'] as $d)
                    <div class="sk-cal-head">{{ $d }}</div>
                @endforeach

                @php $wc = 0; @endphp
                @foreach($calendarDays as $day)
                    @php
                        $dateStr = $day['date']->format('Y-m-d');
                        $classes = 'sk-cal-day';
                        if(!$day['isCurrentMonth']) $classes .= ' other';
                        if($day['hasWedding']) $classes .= ' has-event';
                    @endphp
                    <div class="sk-cal-cell">
                        @if($day['hasWedding'])
                            <a href="{{ route('admin2.laporan', ['tgl_acara_dari'=>$dateStr,'tgl_acara_sampai'=>$dateStr]) }}"
                               class="{{ $classes }}">
                                {{ $day['day'] }}
                                <span class="sk-cal-count">● {{ $day['weddingCount'] }}</span>
                            </a>
                        @else
                            <span class="{{ $classes }}">{{ $day['day'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- DP Follow Up --}}
    <div class="sk-panel">
        <div class="sk-panel-hd">
            <div>
                <div class="sk-panel-title">
                    <i class="bi bi-wallet2" style="color:#f59e0b;margin-right:7px;font-size:.85rem;"></i>
                    DP Follow Up
                </div>
                <p class="sk-panel-sub">Booking yang perlu follow-up pembayaran termin.</p>
            </div>
            <span class="sk-panel-badge" style="background:#fffbeb;color:#92400e;border:1px solid #fde68a;">
                {{ count($dpFollowUp) }} booking
            </span>
        </div>
        <div style="overflow-x:auto;">
            <table class="sk-dp-table">
                <thead>
                    <tr>
                        <th style="padding-left:20px;">Client</th>
                        <th>Paket</th>
                        <th>Tgl Acara</th>
                        <th>Termin</th>
                        <th style="text-align:right;padding-right:20px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dpFollowUp as $b)
                    @php
                        $paid = $b->paymentTerms->where('payment_status','paid')->count();
                        $all  = $b->paymentTerms->count();
                        $pct  = $all > 0 ? round($paid/$all*100) : 0;
                    @endphp
                    <tr onclick="window.location='{{ route('admin2.customer.show', $b->customer_id) }}'">
                        <td style="padding-left:20px;">
                            <div style="font-weight:700;color:#0f1f42;">{{ $b->nama_client ?? $b->customer->nama_client }}</div>
                        </td>
                        <td style="color:#8a9ab8;">{{ $b->details->first()?->paket?->nama_paket ?? '—' }}</td>
                        <td style="color:#5a6b8a;">{{ \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') }}</td>
                        <td>
                            <div class="sk-termin">
                                {{ $paid }}/{{ $all }}
                                <div class="sk-termin-bar">
                                    <div class="sk-termin-fill" style="width:{{ $pct }}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align:right;padding-right:20px;">
                            <span class="sk-amount">Rp {{ number_format((float)$b->total_transaksi,0,',','.') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:36px 16px;color:#8a9ab8;font-size:.82rem;">
                            <i class="bi bi-check-circle-fill" style="color:#10b981;margin-right:7px;"></i>
                            Tidak ada DP yang perlu follow-up
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Booking Terbaru ──────────────────────────────── --}}
<div class="sk-panel">
    <div class="sk-panel-hd">
        <div>
            <div class="sk-panel-title">
                <i class="bi bi-activity" style="color:#6366f1;margin-right:7px;font-size:.85rem;"></i>
                Booking Terbaru
            </div>
            <p class="sk-panel-sub">Pantau update terbaru untuk follow-up cepat.</p>
        </div>
        <a href="{{ route('admin2.pesanan') }}" style="font-size:.75rem;color:#3b82f6;text-decoration:none;font-weight:700;margin-top:3px;">
            Lihat semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table class="sk-book-table">
            <thead>
                <tr>
                    <th style="padding-left:22px;">Client</th>
                    <th>Paket</th>
                    <th>Tgl Acara</th>
                    <th>Status Bayar</th>
                    <th>Status Acara</th>
                    <th style="text-align:right;padding-right:22px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookingTerbaru as $b)
                @php
                    $bc = match($b->job_status){
                        'Selesai'              => 'sk-badge-ok',
                        'Batal'                => 'sk-badge-red',
                        'Booked','Confirmed'   => 'sk-badge-blue',
                        default                => 'sk-badge-warn',
                    };
                @endphp
                <tr onclick="window.location='{{ route('admin2.customer.show', $b->customer_id) }}'">
                    <td style="padding-left:22px;">
                        <div class="sk-client-name">{{ $b->nama_client }}</div>
                    </td>
                    <td><span class="sk-pkg">{{ $b->details->first()?->paket?->nama_paket ?? '—' }}</span></td>
                    <td><span class="sk-date">{{ \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') }}</span></td>
                    <td>
                        <span class="sk-badge {{ $b->status_pembayaran=='Lunas' ? 'sk-badge-ok' : 'sk-badge-warn' }}">
                            {{ $b->status_pembayaran }}
                        </span>
                    </td>
                    <td><span class="sk-badge {{ $bc }}">{{ $b->job_status }}</span></td>
                    <td style="text-align:right;padding-right:22px;">
                        <span class="sk-amount">Rp {{ number_format((float)$b->total_transaksi,0,',','.') }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:44px 16px;color:#8a9ab8;">
                        Belum ada data booking.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>{{-- end sk-wrap --}}
@endsection