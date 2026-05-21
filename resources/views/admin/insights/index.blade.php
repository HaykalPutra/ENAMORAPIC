@extends('layouts.admin')
@section('title','Executive Insights')
@section('hideTopbarTitle', '1')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
/* ── Animations ─────────────────────────────────────────── */
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
@keyframes countUp{from{opacity:0;transform:translateY(8px) scale(.94)}to{opacity:1;transform:translateY(0) scale(1)}}
.a1{animation:fadeUp .42s ease .05s both}
.a2{animation:fadeUp .42s ease .11s both}
.a3{animation:fadeUp .42s ease .17s both}
.a4{animation:fadeUp .42s ease .23s both}
.a5{animation:fadeUp .42s ease .29s both}
.a6{animation:fadeUp .42s ease .35s both}

/* ── Hero header ────────────────────────────────────────── */
.insight-hero {
    background: linear-gradient(135deg, #0B1736 0%, #1A2F6A 100%);
    border-radius: 18px;
    padding: 24px 28px;
    position: relative;
    overflow: hidden;
    margin-bottom: 22px;
}
.insight-hero::before {
    content:'';
    position:absolute;
    top:-60px;right:-60px;
    width:200px;height:200px;
    border-radius:50%;
    background:radial-gradient(circle,rgba(212,169,106,.18) 0%,transparent 70%);
    pointer-events:none;
}
.insight-hero::after {
    content:'';
    position:absolute;
    bottom:-30px;left:240px;
    width:120px;height:120px;
    border-radius:50%;
    background:radial-gradient(circle,rgba(255,255,255,.04) 0%,transparent 70%);
    pointer-events:none;
}

/* ── Filter bar ─────────────────────────────────────────── */
.filter-wrap {
    background:#fff;
    border-radius:14px;
    border:1px solid #E4EAF3;
    padding:16px 20px;
    margin-bottom:22px;
    box-shadow:0 2px 14px rgba(11,23,54,.05);
}
.filter-wrap .form-select,
.filter-wrap .form-control {
    border-radius:9px;
    border:1.5px solid #E4EAF3;
    font-size:.83rem;
    padding:8px 12px;
    transition:border-color .2s,box-shadow .2s;
    color:#0B1736;
}
.filter-wrap .form-select:focus,
.filter-wrap .form-control:focus {
    border-color:#0B1736;
    box-shadow:0 0 0 3px rgba(11,23,54,.07);
    outline:none;
}
.btn-apply {
    background:#0B1736;color:#fff;border:none;
    border-radius:9px;font-weight:700;font-size:.83rem;
    padding:9px 18px;transition:opacity .2s;white-space:nowrap;
}
.btn-apply:hover{opacity:.85;color:#fff;}
.btn-exp {
    border-radius:9px;font-weight:600;font-size:.82rem;
    padding:9px 14px;transition:all .2s;white-space:nowrap;
}

/* ── KPI Cards ──────────────────────────────────────────── */
.kpi-card {
    background:#fff;
    border-radius:14px;
    border:1px solid #E4EAF3;
    padding:18px 20px 16px;
    height:100%;
    position:relative;
    overflow:hidden;
    transition:transform .22s,box-shadow .22s;
}
.kpi-card:hover{transform:translateY(-3px);box-shadow:0 10px 30px rgba(11,23,54,.11);}
.kpi-card::after{
    content:'';
    position:absolute;
    bottom:0;left:0;right:0;
    height:3px;
    border-radius:0 0 14px 14px;
    background:var(--kc,#E4EAF3);
    transition:height .2s;
}
.kpi-card:hover::after{height:4px;}
.kpi-icon{
    width:40px;height:40px;border-radius:11px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.05rem;margin-bottom:12px;
}
.kpi-label{font-size:.68rem;text-transform:uppercase;letter-spacing:.09em;color:#94A3B8;font-weight:700;}
.kpi-value{
    font-size:1.6rem;font-weight:800;color:#0B1736;
    margin-top:5px;line-height:1;letter-spacing:-.03em;
    animation:countUp .55s ease both;
}
.kpi-value-sm{font-size:1.1rem;}

/* ── Lead status badges ─────────────────────────────────── */
.lead-pill {
    display:inline-flex;align-items:center;gap:5px;
    background:#F8FAFC;border:1px solid #E4EAF3;
    border-radius:999px;padding:5px 12px;
    font-size:.75rem;font-weight:600;color:#334155;
    margin:3px 4px 3px 0;
    transition:background .15s;
}
.lead-pill:hover{background:#EFF6FF;border-color:#BFDBFE;color:#1D4ED8;}
.lead-dot{width:7px;height:7px;border-radius:50%;}

/* ── Chart panels ───────────────────────────────────────── */
.chart-panel {
    background:#fff;
    border-radius:16px;
    border:1px solid #E4EAF3;
    box-shadow:0 2px 18px rgba(11,23,54,.06);
    height:100%;
    overflow:hidden;
}
.chart-panel-hd {
    padding:16px 20px 12px;
    border-bottom:1px solid #F1F5FB;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.chart-panel-title{
    font-size:.93rem;
    font-weight:700;
    color:#0B1736;
    display:flex;
    align-items:center;
    gap:8px;
}
.chart-icon{
    width:30px;height:30px;border-radius:8px;
    display:flex;align-items:center;justify-content:center;
    font-size:.85rem;flex-shrink:0;
}
.chart-body{padding:18px 16px 16px;}
</style>
@endsection

@section('content')

{{-- ── HERO ──────────────────────────────────────────────── --}}
<div class="insight-hero a1">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3"
         style="position:relative;z-index:1">
        <div>
            <div style="font-size:.63rem;color:rgba(255,255,255,.4);letter-spacing:.18em;text-transform:uppercase;margin-bottom:6px">
                CEO &nbsp;·&nbsp; Analitik
            </div>
            <h4 style="color:#fff;font-weight:800;margin:0;font-size:1.4rem;letter-spacing:-.02em">
                Executive Insights
            </h4>
            <p style="color:rgba(255,255,255,.5);font-size:.84rem;margin:4px 0 0">
                Ringkasan KPI dan tren bisnis Enamorapic
            </p>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:12px 20px;min-width:110px;text-align:center;">
                <div style="font-size:1.35rem;font-weight:800;color:#fff">{{ number_format($totalBooking) }}</div>
                <div style="font-size:.67rem;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.1em;margin-top:2px">Total Booking</div>
            </div>
            <div style="background:rgba(212,169,106,.18);border:1px solid rgba(212,169,106,.3);border-radius:12px;padding:12px 20px;min-width:110px;text-align:center;">
                <div style="font-size:1.1rem;font-weight:800;color:#D4A96A">{{ $conversionRate }}%</div>
                <div style="font-size:.67rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.1em;margin-top:2px">Conversion</div>
            </div>
        </div>
    </div>
</div>

{{-- ── FILTER BAR ────────────────────────────────────────── --}}
<div class="filter-wrap a2">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-6 col-md-auto">
            <label class="d-block fw-semibold mb-1" style="font-size:.7rem;color:#64748B;text-transform:uppercase;letter-spacing:.06em">Range</label>
            <select name="range" class="form-select" style="width:130px">
                @foreach([3,6,12] as $opt)
                <option value="{{ $opt }}" {{ (int)$range === $opt ? 'selected' : '' }}>{{ $opt }} bulan</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-auto">
            <label class="d-block fw-semibold mb-1" style="font-size:.7rem;color:#64748B;text-transform:uppercase;letter-spacing:.06em">Dari</label>
            <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-control" style="width:150px">
        </div>
        <div class="col-6 col-md-auto">
            <label class="d-block fw-semibold mb-1" style="font-size:.7rem;color:#64748B;text-transform:uppercase;letter-spacing:.06em">Sampai</label>
            <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-control" style="width:150px">
        </div>
        <div class="col-6 col-md-auto">
            <label class="d-block fw-semibold mb-1" style="font-size:.7rem;color:#64748B;text-transform:uppercase;letter-spacing:.06em">Sumber Revenue</label>
            <select name="revenue_source" class="form-select" style="width:175px">
                <option value="payment" {{ ($revenueSource ?? 'payment') === 'payment' ? 'selected' : '' }}>Payment Success</option>
                <option value="booking" {{ ($revenueSource ?? 'payment') === 'booking' ? 'selected' : '' }}>Total Booking</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn-apply">
                <i class="bi bi-funnel-fill me-1"></i> Terapkan
            </button>
        </div>
        <div class="col-auto ms-md-auto">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.insights.pdf', request()->query()) }}"
                   class="btn btn-exp btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                </a>
                <a href="{{ route('admin.insights.excel', request()->query()) }}"
                   class="btn btn-exp btn-outline-success">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Excel
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ── KPI CARDS ─────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-lg-3 a3">
        <div class="kpi-card" style="--kc:#2563EB">
            <div class="kpi-icon" style="background:#EFF6FF;color:#2563EB">
                <i class="bi bi-calendar2-check-fill"></i>
            </div>
            <div class="kpi-label">Total Booking</div>
            <div class="kpi-value">{{ number_format($totalBooking) }}</div>
        </div>
    </div>

    <div class="col-6 col-lg-3 a3" style="animation-delay:.07s">
        <div class="kpi-card" style="--kc:#8B5CF6">
            <div class="kpi-icon" style="background:#F5F3FF;color:#8B5CF6">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div class="kpi-label">DP / Lunas</div>
            <div class="kpi-value kpi-value-sm">
                <span style="color:#D97706">{{ number_format($dpCount) }}</span>
                <span style="color:#CBD5E1;font-weight:400;font-size:1.1rem;margin:0 4px">/</span>
                <span style="color:#16A34A">{{ number_format($lunasCount) }}</span>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3 a3" style="animation-delay:.14s">
        <div class="kpi-card" style="--kc:#10B981">
            <div class="kpi-icon" style="background:#ECFDF5;color:#10B981">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="kpi-label">Revenue Bulan Ini</div>
            <div class="kpi-value kpi-value-sm" style="color:#10B981">
                Rp {{ number_format($revenueThisMonth,0,',','.') }}
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3 a3" style="animation-delay:.21s">
        <div class="kpi-card" style="--kc:#D4A96A">
            <div class="kpi-icon" style="background:#FEF9EE;color:#D97706">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="kpi-label">Conversion Lead → Booking</div>
            <div class="kpi-value" style="color:#D97706">{{ $conversionRate }}%</div>
        </div>
    </div>

    <div class="col-6 col-lg-3 a4">
        <div class="kpi-card" style="--kc:#0891B2">
            <div class="kpi-icon" style="background:#ECFEFF;color:#0891B2">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="kpi-label">Avg. Pelunasan</div>
            <div class="kpi-value">{{ $avgPelunasanDays }} <span style="font-size:.9rem;font-weight:500;color:#94A3B8">hari</span></div>
        </div>
    </div>

    <div class="col-6 col-lg-3 a4" style="animation-delay:.07s">
        <div class="kpi-card" style="--kc:#EC4899">
            <div class="kpi-icon" style="background:#FDF2F8;color:#EC4899">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div class="kpi-label">Paket Terlaris</div>
            <div class="kpi-value" style="font-size:.95rem;margin-top:8px;line-height:1.3;letter-spacing:0">
                {{ $paketPopuler->first()->nama_paket ?? '—' }}
            </div>
        </div>
    </div>

    {{-- Lead per status spans 2 cols --}}
    <div class="col-12 col-lg-6 a4" style="animation-delay:.14s">
        <div class="kpi-card" style="--kc:#64748B;height:100%">
            <div class="kpi-icon" style="background:#F8FAFC;color:#64748B">
                <i class="bi bi-funnel-fill"></i>
            </div>
            <div class="kpi-label" style="margin-bottom:10px">Lead per Status</div>
            <div>
                @php $dotColors=['#94A3B8','#F59E0B','#2563EB','#10B981','#EF4444','#8B5CF6']; $di=0; @endphp
                @foreach($leadStatuses as $key => $label)
                <span class="lead-pill">
                    <span class="lead-dot" style="background:{{ $dotColors[$di++ % count($dotColors)] }}"></span>
                    {{ $label }}: <strong>{{ $leadStatusCounts[$key] ?? 0 }}</strong>
                </span>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- ── CHARTS 2×2 ────────────────────────────────────────── --}}
<div class="row g-4">

    {{-- Trend Booking --}}
    <div class="col-lg-6 a5">
        <div class="chart-panel">
            <div class="chart-panel-hd">
                <div class="chart-panel-title">
                    <div class="chart-icon" style="background:#EFF6FF;color:#2563EB"><i class="bi bi-calendar3"></i></div>
                    Trend Booking per Bulan
                </div>
                <span style="font-size:.7rem;background:#F0F4FF;color:#3451A0;border-radius:6px;padding:4px 10px;font-weight:600">{{ $range }} bln</span>
            </div>
            <div class="chart-body">
                <div style="position:relative;height:220px"><canvas id="chartBooking"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Trend Revenue --}}
    <div class="col-lg-6 a5" style="animation-delay:.07s">
        <div class="chart-panel">
            <div class="chart-panel-hd">
                <div class="chart-panel-title">
                    <div class="chart-icon" style="background:#ECFDF5;color:#10B981"><i class="bi bi-graph-up"></i></div>
                    Trend Pendapatan per Bulan
                </div>
                <span style="font-size:.7rem;background:#ECFDF5;color:#15803D;border-radius:6px;padding:4px 10px;font-weight:600">Revenue</span>
            </div>
            <div class="chart-body">
                <div style="position:relative;height:220px"><canvas id="chartRevenue"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Funnel --}}
    <div class="col-lg-6 a6">
        <div class="chart-panel">
            <div class="chart-panel-hd">
                <div class="chart-panel-title">
                    <div class="chart-icon" style="background:#FEF9EE;color:#D97706"><i class="bi bi-funnel"></i></div>
                    Funnel Lead → Booking
                </div>
            </div>
            <div class="chart-body">
                <div style="position:relative;height:220px"><canvas id="chartFunnel"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Doughnut --}}
    <div class="col-lg-6 a6" style="animation-delay:.07s">
        <div class="chart-panel">
            <div class="chart-panel-hd">
                <div class="chart-panel-title">
                    <div class="chart-icon" style="background:#F5F3FF;color:#8B5CF6"><i class="bi bi-pie-chart-fill"></i></div>
                    Distribusi Status Pembayaran
                </div>
            </div>
            <div class="chart-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div style="position:relative;height:200px"><canvas id="chartPayment"></canvas></div>
                    </div>
                    <div class="col-4">
                        <div style="margin-bottom:14px">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                                <span style="width:10px;height:10px;border-radius:50%;background:#D97706;flex-shrink:0"></span>
                                <span style="font-size:.75rem;color:#64748B">DP</span>
                            </div>
                            <div style="font-size:1.4rem;font-weight:800;color:#D97706">{{ number_format($dpCount) }}</div>
                        </div>
                        <div>
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                                <span style="width:10px;height:10px;border-radius:50%;background:#10B981;flex-shrink:0"></span>
                                <span style="font-size:.75rem;color:#64748B">Lunas</span>
                            </div>
                            <div style="font-size:1.4rem;font-weight:800;color:#10B981">{{ number_format($lunasCount) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const months       = @json($months ?? []);
    const bookingTrend = @json($bookingTrend ?? []);
    const revenueTrend = @json($revenueTrend ?? []);
    const funnelLabels = @json($funnelLabels ?? []);
    const funnelValues = @json($funnelValues ?? []);

    // Fallback: jika data kosong, isi dummy agar canvas tetap render
    const safeMonths       = months.length       ? months       : ['Jan','Feb','Mar','Apr','Mei','Jun'];
    const safeBookingTrend = bookingTrend.length  ? bookingTrend : [0,0,0,0,0,0];
    const safeRevenueTrend = revenueTrend.length  ? revenueTrend : [0,0,0,0,0,0];
    const safeFunnelLabels = funnelLabels.length  ? funnelLabels : ['Prospek','Negosiasi','Booking','Selesai','Hilang'];
    const safeFunnelValues = funnelValues.length  ? funnelValues : [0,0,0,0,0];

    function makeGrad(ctx, c1, c2) {
        const g = ctx.createLinearGradient(0, 0, 0, 220);
        g.addColorStop(0, c1);
        g.addColorStop(1, c2);
        return g;
    }

    const tooltipDefaults = {
        backgroundColor: '#0B1736',
        titleColor: 'rgba(255,255,255,.6)',
        bodyColor: '#fff',
        padding: 12,
        cornerRadius: 10,
    };

    const scaleDefaults = {
        x: {
            grid: { display: false },
            border: { display: false },
            ticks: { color: '#94A3B8', font: { size: 11 } }
        },
        y: {
            grid: { color: '#F1F5FB' },
            border: { display: false },
            ticks: { color: '#94A3B8', font: { size: 11 } }
        }
    };

    /* ── Booking Trend ── */
    const ctxBooking = document.getElementById('chartBooking');
    if (ctxBooking) {
        const ctx = ctxBooking.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: safeMonths,
                datasets: [{
                    label: 'Booking',
                    data: safeBookingTrend,
                    borderColor: '#2563EB',
                    backgroundColor: makeGrad(ctx, 'rgba(37,99,235,.18)', 'rgba(37,99,235,.02)'),
                    tension: .4, fill: true, borderWidth: 2.5,
                    pointRadius: 5, pointHoverRadius: 7,
                    pointBackgroundColor: '#fff', pointBorderColor: '#2563EB', pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { display: false }, tooltip: tooltipDefaults },
                scales: scaleDefaults
            }
        });
    }

    /* ── Revenue Trend ── */
    const ctxRevenue = document.getElementById('chartRevenue');
    if (ctxRevenue) {
        const ctx = ctxRevenue.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: safeMonths,
                datasets: [{
                    label: 'Pendapatan',
                    data: safeRevenueTrend,
                    borderColor: '#10B981',
                    backgroundColor: makeGrad(ctx, 'rgba(16,185,129,.18)', 'rgba(16,185,129,.02)'),
                    tension: .4, fill: true, borderWidth: 2.5,
                    pointRadius: 5, pointHoverRadius: 7,
                    pointBackgroundColor: '#fff', pointBorderColor: '#10B981', pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        ...tooltipDefaults,
                        callbacks: {
                            label: c => '  Rp ' + new Intl.NumberFormat('id-ID').format(c.parsed.y)
                        }
                    }
                },
                scales: {
                    ...scaleDefaults,
                    y: {
                        ...scaleDefaults.y,
                        ticks: {
                            ...scaleDefaults.y.ticks,
                            callback: v => (v / 1000000).toFixed(0) + 'jt'
                        }
                    }
                }
            }
        });
    }

    /* ── Funnel Bar ── */
    const ctxFunnel = document.getElementById('chartFunnel');
    if (ctxFunnel) {
        const barColors = ['#94A3B8', '#F59E0B', '#2563EB', '#10B981', '#EF4444', '#8B5CF6'];
        new Chart(ctxFunnel, {
            type: 'bar',
            data: {
                labels: safeFunnelLabels,
                datasets: [{
                    label: 'Jumlah Lead',
                    data: safeFunnelValues,
                    backgroundColor: safeFunnelLabels.map((_, i) => barColors[i % barColors.length]),
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: .6,
                    categoryPercentage: .7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipDefaults },
                scales: scaleDefaults
            }
        });
    }

    /* ── Doughnut ── */
    const ctxPayment = document.getElementById('chartPayment');
    if (ctxPayment) {
        new Chart(ctxPayment, {
            type: 'doughnut',
            data: {
                labels: ['DP', 'Lunas'],
                datasets: [{
                    data: [{{ $dpCount ?? 0 }}, {{ $lunasCount ?? 0 }}],
                    backgroundColor: ['#D97706', '#10B981'],
                    hoverBackgroundColor: ['#B45309', '#059669'],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: { ...tooltipDefaults }
                }
            }
        });
    }

});
</script>
@endsection