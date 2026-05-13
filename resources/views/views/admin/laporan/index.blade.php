@extends('layouts.admin')
@section('title','Laporan Keuangan')
@section('styles')
<style>
.page-enter{opacity:0;animation:fadeIn 0.8s ease forwards;}
@keyframes fadeIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.custom-card{border-radius:12px;border:none;box-shadow:0 2px 12px rgba(0,0,0,0.06);background:#fff;overflow:hidden;}
.filter-card{border-radius:12px;border:none;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;box-shadow:0 4px 20px rgba(102,126,234,0.3);}
.table thead th{background-color:#f8f9fa;border-bottom:2px solid #e9ecef;font-weight:600;color:#495057;padding:16px;}
.table tbody td{padding:16px;vertical-align:middle;}
.total-row-web{background-color:#f8f9fa;font-size:1.1rem;border-top:2px solid #dee2e6;}
</style>
@endsection

@section('content')
<div class="container-fluid page-enter py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-chart-line text-primary me-2"></i>Laporan Keuangan</h3>
            <p class="text-muted mb-0">Rekapitulasi pendapatan dan transaksi</p>
        </div>
    </div>

    {{-- FILTER CARD --}}
    <div class="card filter-card mb-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.laporan.index') }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-3">
                    <label class="form-label fw-bold text-white">Dari Tanggal</label>
                    <input type="date" name="tgl_dari" class="form-control bg-white border-0"
                           value="{{ $tglDari }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-white">Sampai Tanggal</label>
                    <input type="date" name="tgl_sampai" class="form-control bg-white border-0"
                           value="{{ $tglSampai }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-white">Status Pembayaran</label>
                    <select name="status_bayar" class="form-select bg-white border-0">
                        <option value="All" {{ $statusBayar=='All'?'selected':'' }}>Semua Status</option>
                        <option value="Lunas" {{ $statusBayar=='Lunas'?'selected':'' }}>Lunas</option>
                        <option value="DP" {{ $statusBayar=='DP'?'selected':'' }}>DP / Belum Lunas</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" name="filter" class="btn btn-light w-100 fw-bold text-primary shadow-sm">
                        <i class="fas fa-filter me-1"></i>Tampil
                    </button>
                    <a href="{{ route('admin.laporan.pdf', ['tgl_dari'=>$tglDari,'tgl_sampai'=>$tglSampai,'status_bayar'=>$statusBayar]) }}"
                       target="_blank" class="btn btn-outline-light w-100 fw-bold">
                        <i class="fas fa-file-pdf me-1"></i>PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tgl Transaksi</th>
                            <th>Nama Client</th>
                            <th>Tgl Acara</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanData as $no => $d)
                        <tr class="{{ $d->status_acara=='Batal' ? 'bg-danger bg-opacity-10' : '' }}">
                            <td class="ps-4 text-muted fw-bold">{{ $no+1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($d->tgl_booking)->format('d/m/Y') }}</td>
                            <td><span class="fw-bold text-dark">{{ $d->nama_client }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($d->tgl_acara)->format('d M Y') }}</td>
                            <td>
                                @if($d->status_acara == 'Batal')
                                    <span class="badge bg-danger">DIBATALKAN</span>
                                @elseif($d->status_pembayaran == 'Lunas')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">DP</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 fw-bold">
                                @if($d->status_acara == 'Batal')
                                    <span class="text-decoration-line-through text-muted small me-2">
                                        Rp {{ number_format($d->total_transaksi,0,',','.') }}
                                    </span>
                                    <span class="text-danger">0</span>
                                @else
                                    <span class="text-dark">Rp {{ number_format($d->total_transaksi,0,',','.') }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-search-dollar fa-3x mb-3 d-block opacity-50"></i>
                                Tidak ada data transaksi pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="total-row-web">
                            <td colspan="5" class="text-end fw-bold py-3">TOTAL PENDAPATAN BERSIH</td>
                            <td class="text-end fw-bold py-3 pe-4 text-primary fs-5">
                                Rp {{ number_format($totalOmzet,0,',','.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
