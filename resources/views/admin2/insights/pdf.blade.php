<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Executive Insights</title>
<style>
body { font-family: Arial, sans-serif; font-size: 12px; color: #111827; }
h1 { font-size: 18px; margin-bottom: 4px; }
small { color: #6b7280; }
.table { width: 100%; border-collapse: collapse; margin-top: 12px; }
.table th, .table td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; }
.table th { background: #f3f4f6; }
.section { margin-top: 16px; }
</style>
</head>
<body>
    <h1>Executive Insights</h1>
    <small>Range: {{ $startDate }} s/d {{ $endDate }}</small>

    <div class="section">
        <table class="table">
            <tr><th>Total Booking</th><td>{{ $totalBooking }}</td></tr>
            <tr><th>DP</th><td>{{ $dpCount }}</td></tr>
            <tr><th>Lunas</th><td>{{ $lunasCount }}</td></tr>
            <tr><th>Pendapatan</th><td>Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</td></tr>
            <tr><th>Conversion Lead -> Booking</th><td>{{ $conversionRate }}%</td></tr>
            <tr><th>Average Pelunasan</th><td>{{ $avgPelunasanDays }} hari</td></tr>
        </table>
    </div>

    <div class="section">
        <h3>Trend Booking</h3>
        <table class="table">
            <tr><th>Bulan</th><th>Jumlah Booking</th></tr>
            @foreach($months as $idx => $label)
                <tr><td>{{ $label }}</td><td>{{ $bookingTrend[$idx] ?? 0 }}</td></tr>
            @endforeach
        </table>
    </div>

    <div class="section">
        <h3>Trend Pendapatan</h3>
        <table class="table">
            <tr><th>Bulan</th><th>Pendapatan</th></tr>
            @foreach($months as $idx => $label)
                <tr><td>{{ $label }}</td><td>{{ number_format($revenueTrend[$idx] ?? 0, 0, ',', '.') }}</td></tr>
            @endforeach
        </table>
    </div>

    <div class="section">
        <h3>Lead per Status</h3>
        <table class="table">
            <tr><th>Status</th><th>Jumlah</th></tr>
            @foreach($leadStatuses as $key => $label)
                <tr><td>{{ $label }}</td><td>{{ $leadStatusCounts[$key] ?? 0 }}</td></tr>
            @endforeach
        </table>
    </div>

    <div class="section">
        <h3>Funnel Lead -> Booking</h3>
        <table class="table">
            <tr><th>Stage</th><th>Jumlah</th></tr>
            @foreach($funnelLabels as $index => $label)
                <tr><td>{{ $label }}</td><td>{{ $funnelValues[$index] ?? 0 }}</td></tr>
            @endforeach
        </table>
    </div>

    <div class="section">
        <h3>Paket Terlaris</h3>
        <table class="table">
            <tr><th>Paket</th><th>Jumlah</th></tr>
            @foreach($paketPopuler as $paket)
                <tr><td>{{ $paket->nama_paket }}</td><td>{{ $paket->total }}</td></tr>
            @endforeach
        </table>
    </div>
</body>
</html>
