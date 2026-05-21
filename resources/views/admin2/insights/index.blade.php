@extends('layouts.admin2')
@section('title','Executive Insights')
@section('hideTopbarTitle', '1')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
.insight-card {
    background:#fff;
    border-radius:16px;
    border:1px solid #e8edf5;
    padding:18px 20px;
    box-shadow:0 8px 20px rgba(11,23,54,.07);
}
.insight-label {
    font-size:.7rem;
    text-transform:uppercase;
    letter-spacing:.08em;
    color:#94a3b8;
    font-weight:600;
}
.insight-value {
    font-size:1.7rem;
    font-weight:800;
    color:#0b1736;
    margin-top:6px;
}
.panel {
    background:#fff;
    border-radius:16px;
    border:1px solid #e8edf5;
    box-shadow:0 8px 20px rgba(11,23,54,.07);
}
.panel-hd {
    padding:16px 18px;
    border-bottom:1px solid #eef2f7;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
.panel-title { font-weight:700; color:#0b1736; }
</style>
@endsection

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Executive Insights</h4>
        <div class="text-muted">Ringkasan KPI dan tren bisnis</div>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
            <label class="text-muted small">Range</label>
            <select name="range" class="form-select form-select-sm" style="width:140px;">
                @foreach([3,6,12] as $opt)
                    <option value="{{ $opt }}" {{ (int) $range === $opt ? 'selected' : '' }}>{{ $opt }} bulan</option>
                @endforeach
            </select>
            <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-control form-control-sm">
            <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-control form-control-sm">
            <select name="revenue_source" class="form-select form-select-sm" style="width:170px;">
                <option value="payment" {{ ($revenueSource ?? 'payment') === 'payment' ? 'selected' : '' }}>Payment Success</option>
                <option value="booking" {{ ($revenueSource ?? 'payment') === 'booking' ? 'selected' : '' }}>Total Booking</option>
            </select>
            <button class="btn btn-sm btn-outline-primary" type="submit">Terapkan</button>
        </form>
        <div class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin2.insights.pdf', request()->query()) }}">Export PDF</a>
            <a class="btn btn-sm btn-outline-success" href="{{ route('admin2.insights.excel', request()->query()) }}">Export Excel</a>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="insight-card h-100">
            <div class="insight-label">Total Booking</div>
            <div class="insight-value">{{ number_format($totalBooking) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="insight-card h-100">
            <div class="insight-label">DP vs Lunas</div>
            <div class="insight-value">{{ number_format($dpCount) }} / {{ number_format($lunasCount) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="insight-card h-100">
            <div class="insight-label">Pendapatan Bulan Ini</div>
            <div class="insight-value">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="insight-card h-100">
            <div class="insight-label">Conversion Lead → Booking</div>
            <div class="insight-value">{{ $conversionRate }}%</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="insight-card h-100">
            <div class="insight-label">Average Pelunasan</div>
            <div class="insight-value">{{ $avgPelunasanDays }} hari</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="insight-card h-100">
            <div class="insight-label">Paket Terlaris</div>
            <div class="insight-value" style="font-size:1.1rem;">
                {{ $paketPopuler->first()->nama_paket ?? '-' }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="insight-card h-100">
            <div class="insight-label">Lead per Status</div>
            <div class="mt-2">
                @foreach($leadStatuses as $key => $label)
                    <span class="badge bg-light text-dark border me-2 mb-2">
                        {{ $label }}: {{ $leadStatusCounts[$key] ?? 0 }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="panel-hd">
                <div class="panel-title">Trend Booking per Bulan</div>
            </div>
            <div class="p-3">
                <canvas id="chartBooking"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="panel-hd">
                <div class="panel-title">Trend Pendapatan per Bulan</div>
            </div>
            <div class="p-3">
                <canvas id="chartRevenue"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="panel-hd">
                <div class="panel-title">Funnel Lead → Booking</div>
            </div>
            <div class="p-3">
                <canvas id="chartFunnel"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="panel-hd">
                <div class="panel-title">Distribusi Status Pembayaran</div>
            </div>
            <div class="p-3">
                <canvas id="chartPayment"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
const months = @json($months);
const bookingTrend = @json($bookingTrend);
const revenueTrend = @json($revenueTrend);
const funnelLabels = @json($funnelLabels);
const funnelValues = @json($funnelValues);

new Chart(document.getElementById('chartBooking'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Booking',
            data: bookingTrend,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.15)',
            tension: 0.35,
            fill: true
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

new Chart(document.getElementById('chartRevenue'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Pendapatan',
            data: revenueTrend,
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22,163,74,0.15)',
            tension: 0.35,
            fill: true
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

new Chart(document.getElementById('chartFunnel'), {
    type: 'bar',
    data: {
        labels: funnelLabels,
        datasets: [{
            label: 'Jumlah Lead',
            data: funnelValues,
            backgroundColor: ['#94a3b8','#f59e0b','#3b82f6','#10b981']
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

new Chart(document.getElementById('chartPayment'), {
    type: 'doughnut',
    data: {
        labels: ['DP', 'Lunas'],
        datasets: [{
            data: [{{ $dpCount }}, {{ $lunasCount }}],
            backgroundColor: ['#60a5fa', '#34d399']
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});
</script>
@endsection
