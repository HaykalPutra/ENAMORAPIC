@extends('layouts.admin2')
@section('title','Laporan Keuangan')
@section('hideTopbarTitle', '1')

@section('styles')
<style>
.summary-card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    background: linear-gradient(135deg, #ffffff 0%, #f7f9fc 100%);
}
.summary-label {
    font-size: 0.75rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #6c757d;
    font-weight: 700;
}
.summary-value {
    font-size: 1.3rem;
    font-weight: 800;
    margin-top: 6px;
}
.report-shell {
    background:#ffffff;
    border:1px solid #e7edf8;
    border-radius:16px;
    box-shadow:0 10px 28px rgba(11,23,54,.07);
}
.report-table thead th {
    background:#f7f9fd;
    color:#6f819f;
    font-size:.72rem;
    letter-spacing:.07em;
    text-transform:uppercase;
    border-bottom:1px solid #e5ecf8;
}
.report-table td { vertical-align:middle; }
.report-chip {
    background:#eff4ff;
    border:1px solid #d9e6ff;
    color:#34517e;
    padding:5px 10px;
    border-radius:999px;
    font-size:.7rem;
    font-weight:700;
}
.report-shell .table-responsive {
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.report-shell .table-responsive::-webkit-scrollbar {
    height: 0;
}
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-bar-chart-line" style="color:#d4af37; margin-right:10px;"></i>Ringkasan Keuangan</h3>
        <p class="text-secondary mb-0">Rekap pendapatan booking berdasarkan bulan dan tahun.</p>
    </div>
</div>

{{-- FILTER --}}
<div class="card mb-4" style="background:linear-gradient(135deg,#1e293b,#334155);border:none;">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-bold text-white">Bulan</label>
                <select name="bulan" class="form-select bg-white border-0">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                    <option value="{{ $i+1 }}" {{ request('bulan') == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold text-white">Tahun</label>
                <select name="tahun" class="form-select bg-white border-0">
                    @for($y = date('Y'); $y >= date('Y')-4; $y--)
                    <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold text-white">Status Bayar</label>
                <select name="filter_bayar" class="form-select bg-white border-0">
                    <option value="All" {{ request('filter_bayar', 'All') == 'All' ? 'selected' : '' }}>Semua</option>
                    <option value="DP" {{ request('filter_bayar') == 'DP' ? 'selected' : '' }}>DP</option>
                    <option value="Lunas" {{ request('filter_bayar') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-light fw-bold text-dark flex-grow-1">
                    <i class="fas fa-filter me-1"></i>Show
                </button>
                <a href="{{ route('admin2.laporan.pdf', request()->all()) }}" target="_blank"
                   class="btn btn-outline-light fw-bold flex-grow-1">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('admin2.laporan.excel', request()->all()) }}"
                   class="btn btn-outline-light fw-bold flex-grow-1">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>Export Excel
                </a>
            </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card summary-card p-3 h-100">
            <div class="summary-label">Total Pendapatan Masuk</div>
            <div class="summary-value text-success">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card p-3 h-100">
            <div class="summary-label">Total Pendapatan (DP)</div>
            <div class="summary-value text-warning">Rp {{ number_format($totalPendapatanDp,0,',','.') }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card p-3 h-100">
            <div class="summary-label">Total Sisa Tagihan</div>
            <div class="summary-value text-danger">Rp {{ number_format($totalSisa,0,',','.') }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card p-3 h-100">
            <div class="summary-label">Total Semua Estimasi Booking</div>
            <div class="summary-value text-primary">Rp {{ number_format($totalEstimasi,0,',','.') }}</div>
        </div>
    </div>
</div>

<div class="report-shell">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0 report-table">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Tgl Booking</th>
                        <th>Nama Client</th>
                        <th>Paket</th>
                        <th>Tgl Acara</th>
                        <th>Status Bayar</th>
                        <th>Status Acara</th>
                        <th style="min-width:80px;">Job Status</th>
                        <th class="text-end">Total Booking</th>
                        <th class="text-end">Nominal Masuk</th>
                        <th class="text-end pe-4">Sisa Tagihan</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $no => $b)
                    @php
                        $namaPaket = $b->details->first()?->paket?->nama_paket ?? '-';
                        $badge = match($b->job_status){ 'Selesai'=>'success','Batal'=>'danger','Booked','Confirmed'=>'primary',default=>'warning' };
                    @endphp
                    <tr class="{{ $b->job_status=='Batal' ? 'opacity-50' : '' }}">
                        <td class="ps-4 text-muted fw-bold">{{ $no + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($b->tgl_booking)->format('d/m/Y') }}</td>
                        <td>
                            @if(!empty($b->customer_id))
                                <a href="{{ route('admin2.customer.show', $b->customer_id) }}" class="fw-bold text-decoration-none">
                                    {{ $b->nama_client }}
                                </a>
                            @else
                                <strong>{{ $b->nama_client }}</strong>
                            @endif
                        </td>
                        <td><span class="report-chip">{{ $namaPaket }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') }}</td>
                        <td>
                            <span class="badge {{ $b->status_pembayaran=='Lunas'?'bg-success':'bg-warning text-dark' }}">
                                {{ $b->status_pembayaran }}
                            </span>
                        </td>
                        <td><span class="badge bg-{{ $badge }}">{{ $b->job_status }}</span></td>
                        <td class="text-end fw-bold">
                            @if($b->job_status == 'Batal')
                                <span class="text-decoration-line-through text-muted">Rp {{ number_format((float) $b->total_transaksi,0,',','.') }}</span>
                            @else
                                Rp {{ number_format((float) $b->total_transaksi,0,',','.') }}
                            @endif
                        </td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format((int) $b->nominal_masuk,0,',','.') }}</td>
                        <td class="text-end pe-4 fw-bold text-warning">Rp {{ number_format((int) $b->sisa_tagihan,0,',','.') }}</td>
                        <td class="text-center pe-4">
                            <a href="{{ route('admin2.laporan.invoice', $b->booking_id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-receipt me-1"></i>Invoice
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
                            Tidak ada data pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background:#1e293b;color:white;">
                        <td colspan="10" class="text-end fw-bold ps-4 py-3">TOTAL PENDAPATAN MASUK</td>
                        <td class="text-end pe-4 fw-bold py-3" style="color:#43e97b;font-size:1.1rem;">
                            Rp {{ number_format($totalPendapatan,0,',','.') }}
                        </td>
                    </tr>
                    <tr style="background:#243348;color:white;">
                        <td colspan="10" class="text-end fw-bold ps-4 py-3">TOTAL MASUK DARI DP</td>
                        <td class="text-end pe-4 fw-bold py-3" style="color:#ffc107;font-size:1.05rem;">
                            Rp {{ number_format($totalPendapatanDp,0,',','.') }}
                        </td>
                    </tr>
                    <tr style="background:#2b3d57;color:white;">
                        <td colspan="10" class="text-end fw-bold ps-4 py-3">TOTAL ESTIMASI BOOKING</td>
                        <td class="text-end pe-4 fw-bold py-3" style="color:#7cc0ff;font-size:1.05rem;">
                            Rp {{ number_format($totalEstimasi,0,',','.') }}
                        </td>
                    </tr>
                    <tr style="background:#374b69;color:white;">
                        <td colspan="10" class="text-end fw-bold ps-4 py-3">TOTAL SISA TAGIHAN</td>
                        <td class="text-end pe-4 fw-bold py-3" style="color:#ffcc66;font-size:1.05rem;">
                            Rp {{ number_format($totalSisa,0,',','.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
