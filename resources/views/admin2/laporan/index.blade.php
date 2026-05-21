@extends('layouts.admin2')
@section('title','Laporan Keuangan')
@section('hideTopbarTitle', '1')

@section('styles')
<style>
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
.a1{animation:fadeUp .4s ease .04s both}
.a2{animation:fadeUp .4s ease .10s both}
.a3{animation:fadeUp .4s ease .16s both}
.a4{animation:fadeUp .4s ease .22s both}

/* ── Hero ─────────────────────────────────── */
.lap-hero{
    background:linear-gradient(135deg,#0B1736 0%,#1A2F6A 100%);
    border-radius:18px;padding:26px 30px;
    position:relative;overflow:hidden;margin-bottom:22px;
}
.lap-hero::before{
    content:'';position:absolute;top:-60px;right:-60px;
    width:200px;height:200px;border-radius:50%;
    background:radial-gradient(circle,rgba(212,169,106,.18) 0%,transparent 70%);
    pointer-events:none;
}

/* ── Filter ───────────────────────────────── */
.filter-bar{
    background:#fff;border-radius:14px;
    border:1px solid #E4EAF3;padding:18px 22px;
    margin-bottom:22px;
    box-shadow:0 2px 14px rgba(11,23,54,.05);
}
.filter-bar .form-select,
.filter-bar .form-control{
    border-radius:9px;border:1.5px solid #E4EAF3;
    font-size:.84rem;padding:9px 12px;color:#0B1736;
    transition:border-color .2s,box-shadow .2s;
}
.filter-bar .form-select:focus,
.filter-bar .form-control:focus{
    border-color:#0B1736;box-shadow:0 0 0 3px rgba(11,23,54,.07);outline:none;
}
.filter-label{
    display:block;font-size:.68rem;font-weight:700;
    text-transform:uppercase;letter-spacing:.08em;
    color:#94A3B8;margin-bottom:5px;
}
.btn-filter{
    background:#0B1736;color:#fff;border:none;
    border-radius:9px;font-weight:700;font-size:.84rem;
    padding:10px 20px;transition:opacity .2s;white-space:nowrap;
    display:inline-flex;align-items:center;gap:6px;width:100%;
    justify-content:center;
}
.btn-filter:hover{opacity:.85;color:#fff;}
.btn-exp{
    border-radius:9px;font-weight:600;font-size:.82rem;
    padding:10px 16px;transition:all .18s;white-space:nowrap;
    display:inline-flex;align-items:center;gap:6px;width:100%;
    justify-content:center;
}

/* ── Summary cards ────────────────────────── */
.sum-card{
    background:#fff;border-radius:14px;
    border:1px solid #E4EAF3;padding:18px 20px;
    box-shadow:0 2px 14px rgba(11,23,54,.05);
    transition:transform .2s,box-shadow .2s;
    position:relative;overflow:hidden;height:100%;
}
.sum-card:hover{transform:translateY(-3px);box-shadow:0 8px 26px rgba(11,23,54,.1);}
.sum-card::after{
    content:'';position:absolute;bottom:0;left:0;right:0;
    height:3px;border-radius:0 0 14px 14px;
    background:var(--sc,#E4EAF3);
}
.sum-icon{
    width:40px;height:40px;border-radius:11px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;margin-bottom:12px;
}
.sum-label{font-size:.68rem;text-transform:uppercase;letter-spacing:.08em;color:#94A3B8;font-weight:700;}
.sum-value{font-size:1.15rem;font-weight:800;color:#0B1736;margin-top:5px;letter-spacing:-.02em;}

/* ── Table shell ──────────────────────────── */
.tbl-shell{
    background:#fff;border-radius:16px;
    border:1px solid #E4EAF3;
    box-shadow:0 2px 20px rgba(11,23,54,.06);
    overflow:hidden;
}
.tbl-top-bar{
    display:flex;align-items:center;justify-content:space-between;
    padding:14px 18px;border-bottom:1px solid #F1F5FB;
    flex-wrap:wrap;gap:10px;
}
.show-label{font-size:.78rem;color:#64748B;font-weight:500;display:flex;align-items:center;gap:8px;}
.show-select{
    border:1.5px solid #E4EAF3;border-radius:8px;
    font-size:.8rem;padding:5px 10px;color:#0B1736;
    font-weight:600;outline:none;cursor:pointer;
    transition:border-color .2s;
}
.show-select:focus{border-color:#0B1736;}
.tbl-info{font-size:.75rem;color:#94A3B8;}

.tbl-shell .table-responsive{
    overflow-x:auto;
    scrollbar-width:thin;scrollbar-color:#E2E8F0 transparent;
}
.tbl-shell .table-responsive::-webkit-scrollbar{height:4px;}
.tbl-shell .table-responsive::-webkit-scrollbar-thumb{background:#E2E8F0;border-radius:4px;}

.tbl-shell table{min-width:1080px;}
.tbl-shell thead th{
    background:#FAFBFD;color:#94A3B8;
    font-size:.67rem;letter-spacing:.09em;
    text-transform:uppercase;font-weight:700;
    padding:12px 14px;border-bottom:1.5px solid #E4EAF3;
    white-space:nowrap;
}
.tbl-shell td{
    padding:12px 14px;font-size:.845rem;
    color:#334155;vertical-align:middle;
    border-bottom:1px solid #F1F5FB;
}
.tbl-shell tbody tr:last-child td{border-bottom:none;}
.tbl-shell tbody tr{transition:background .13s;}
.tbl-shell tbody tr:hover td{background:#F7F9FD;}

/* badges */
.bb{border-radius:6px;padding:3px 10px;font-size:.7rem;font-weight:700;display:inline-block;white-space:nowrap;}
.bb-lunas  {background:#DCFCE7;color:#15803D;}
.bb-dp     {background:#FEF3C7;color:#B45309;}
.bb-selesai{background:#DCFCE7;color:#15803D;}
.bb-booked {background:#EDE9FE;color:#6D28D9;}
.bb-batal  {background:#FEE2E2;color:#B91C1C;}
.bb-warn   {background:#FEF9C3;color:#A16207;}

.pkg-chip{
    display:inline-block;background:#F0F4FF;
    border:1px solid #D9E6FF;color:#3451A0;
    padding:3px 10px;border-radius:999px;
    font-size:.7rem;font-weight:700;
    white-space:nowrap;max-width:155px;
    overflow:hidden;text-overflow:ellipsis;vertical-align:middle;
}

.inv-btn{
    display:inline-flex;align-items:center;gap:5px;
    background:#F0F4FF;color:#3451A0;
    border:1px solid #D9E6FF;border-radius:8px;
    padding:5px 12px;font-size:.74rem;font-weight:600;
    text-decoration:none;transition:all .15s;white-space:nowrap;
}
.inv-btn:hover{background:#0B1736;color:#fff;border-color:#0B1736;}

/* ── tfoot summary ────────────────────────── */
.tbl-footer{border-top:2px solid #E4EAF3;}
.tf-row{
    display:flex;align-items:center;justify-content:space-between;
    padding:12px 18px;border-bottom:1px solid #F1F5FB;
}
.tf-row:last-child{border-bottom:none;}
.tf-row-label{
    display:flex;align-items:center;gap:10px;
    font-size:.78rem;font-weight:600;color:#64748B;
}
.tf-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
.tf-row-value{font-size:.95rem;font-weight:800;white-space:nowrap;}

/* ── Pagination ───────────────────────────── */
.pagi-wrap{
    display:flex;align-items:center;justify-content:center;
    padding:14px 18px;border-top:1px solid #F1F5FB;
    gap:4px;flex-wrap:wrap;
}
.pagi-btn{
    min-width:34px;height:34px;border-radius:9px;
    border:1.5px solid #E4EAF3;background:#fff;
    color:#64748B;font-size:.8rem;font-weight:600;
    display:inline-flex;align-items:center;justify-content:center;
    cursor:pointer;transition:all .16s;padding:0 10px;
    text-decoration:none;
}
.pagi-btn:hover{border-color:#0B1736;color:#0B1736;background:#F0F4FF;}
.pagi-btn.active{background:#0B1736;color:#fff;border-color:#0B1736;}
.pagi-btn.disabled{opacity:.38;pointer-events:none;}
.pagi-ellipsis{min-width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;color:#94A3B8;font-size:.82rem;}
</style>
@endsection

@section('content')

{{-- Hero ──────────────────────────────────────────────────── --}}
<div class="lap-hero a1">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3"
         style="position:relative;z-index:1">
        <div>
            <div style="font-size:.63rem;color:rgba(255,255,255,.4);letter-spacing:.18em;text-transform:uppercase;margin-bottom:5px">Keuangan</div>
            <h4 style="color:#fff;font-weight:800;margin:0;font-size:1.35rem;letter-spacing:-.02em">Laporan Keuangan</h4>
            <p style="color:rgba(255,255,255,.5);font-size:.83rem;margin:4px 0 0">Rekap pendapatan booking berdasarkan bulan dan tahun</p>
        </div>
        <div style="background:rgba(212,169,106,.18);border:1px solid rgba(212,169,106,.28);border-radius:12px;padding:12px 20px;text-align:center;min-width:110px;">
            <div style="font-size:1.2rem;font-weight:800;color:#D4A96A">{{ $bookings->count() }}</div>
            <div style="font-size:.67rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.1em;margin-top:2px">Total Data</div>
        </div>
    </div>
</div>

{{-- Filter ─────────────────────────────────────────────────── --}}
<div class="filter-bar a2">
    <form method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-6 col-md-2">
                <label class="filter-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                    <option value="{{ $i+1 }}" {{ request('bulan') == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="filter-label">Tahun</label>
                <select name="tahun" class="form-select">
                    @for($y = date('Y'); $y >= date('Y')-4; $y--)
                    <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="filter-label">Status Bayar</label>
                <select name="filter_bayar" class="form-select">
                    <option value="All" {{ request('filter_bayar','All')=='All' ? 'selected' : '' }}>Semua</option>
                    <option value="DP"    {{ request('filter_bayar')=='DP'    ? 'selected' : '' }}>DP</option>
                    <option value="Lunas" {{ request('filter_bayar')=='Lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="filter-label">&nbsp;</label>
                <button type="submit" class="btn-filter">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
            </div>
            <div class="col-6 col-md-2">
                <label class="filter-label">&nbsp;</label>
                <a href="{{ route('admin2.laporan.pdf', request()->all()) }}" target="_blank"
                   class="btn btn-exp btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>
            <div class="col-6 col-md-2">
                <label class="filter-label">&nbsp;</label>
                <a href="{{ route('admin2.laporan.excel', request()->all()) }}"
                   class="btn btn-exp btn-outline-success">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Excel
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Summary cards ──────────────────────────────────────────── --}}
<div class="row g-3 mb-4 a3">
    <div class="col-6 col-xl-3">
        <div class="sum-card" style="--sc:#10B981">
            <div class="sum-icon" style="background:#ECFDF5;color:#10B981"><i class="bi bi-wallet2"></i></div>
            <div class="sum-label">Total Pendapatan Masuk</div>
            <div class="sum-value" style="color:#10B981">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="sum-card" style="--sc:#F59E0B">
            <div class="sum-icon" style="background:#FFFBEB;color:#D97706"><i class="bi bi-hourglass-split"></i></div>
            <div class="sum-label">Total Pendapatan (DP)</div>
            <div class="sum-value" style="color:#D97706">Rp {{ number_format($totalPendapatanDp,0,',','.') }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="sum-card" style="--sc:#EF4444">
            <div class="sum-icon" style="background:#FEF2F2;color:#EF4444"><i class="bi bi-receipt-cutoff"></i></div>
            <div class="sum-label">Total Sisa Tagihan</div>
            <div class="sum-value" style="color:#EF4444">Rp {{ number_format($totalSisa,0,',','.') }}</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="sum-card" style="--sc:#2563EB">
            <div class="sum-icon" style="background:#EFF6FF;color:#2563EB"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="sum-label">Total Estimasi Booking</div>
            <div class="sum-value" style="color:#2563EB">Rp {{ number_format($totalEstimasi,0,',','.') }}</div>
        </div>
    </div>
</div>

{{-- Table ─────────────────────────────────────────────────── --}}
<div class="tbl-shell a4">

    {{-- Top bar: show entries + info ──────────────────────── --}}
    <div class="tbl-top-bar">
        <div class="show-label">
            Tampilkan
            <select class="show-select" id="perPageSelect">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all">Semua</option>
            </select>
            data per halaman
        </div>
        <div class="tbl-info" id="tblInfo"></div>
    </div>

    {{-- Table ─────────────────────────────────────────────── --}}
    <div class="table-responsive">
        <table class="table mb-0" id="mainTable">
            <thead>
                <tr>
                    <th class="ps-4" style="width:42px">No</th>
                    <th>Tgl Booking</th>
                    <th>Nama Client</th>
                    <th>Paket</th>
                    <th>Tgl Acara</th>
                    <th>Status Bayar</th>
                    <th>Status Acara</th>
                    <th class="text-end">Total Booking</th>
                    <th class="text-end">Nominal Masuk</th>
                    <th class="text-end pe-3">Sisa Tagihan</th>
                    <th class="text-center pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($bookings as $no => $b)
                @php
                    $namaPaket = $b->details->first()?->paket?->nama_paket ?? '-';
                    $isBatal   = strtolower($b->job_status ?? '') === 'batal';
                    $jobClass  = match(strtolower($b->job_status ?? '')) {
                        'selesai'   => 'bb bb-selesai',
                        'batal'     => 'bb bb-batal',
                        'booked'    => 'bb bb-booked',
                        'confirmed' => 'bb bb-booked',
                        default     => 'bb bb-warn',
                    };
                    $payClass = $b->status_pembayaran === 'Lunas' ? 'bb bb-lunas' : 'bb bb-dp';
                @endphp
                <tr class="data-row" style="{{ $isBatal ? 'opacity:.5' : '' }}">
                    <td class="ps-4" style="color:#CBD5E1;font-weight:700;font-size:.78rem">{{ $no+1 }}</td>
                    <td style="white-space:nowrap;color:#64748B;font-size:.82rem">{{ \Carbon\Carbon::parse($b->tgl_booking)->format('d/m/Y') }}</td>
                    <td>
                        @if(!empty($b->customer_id))
                            <a href="{{ route('admin2.customer.show', $b->customer_id) }}"
                               style="font-weight:700;color:#0B1736;text-decoration:none">{{ $b->nama_client }}</a>
                        @else
                            <span style="font-weight:700;color:#0B1736">{{ $b->nama_client }}</span>
                        @endif
                    </td>
                    <td><span class="pkg-chip" title="{{ $namaPaket }}">{{ $namaPaket }}</span></td>
                    <td style="white-space:nowrap;font-size:.82rem">{{ \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') }}</td>
                    <td><span class="{{ $payClass }}">{{ $b->status_pembayaran }}</span></td>
                    <td><span class="{{ $jobClass }}">{{ $b->job_status }}</span></td>
                    <td class="text-end" style="font-weight:700;white-space:nowrap">
                        @if($isBatal)
                            <span style="text-decoration:line-through;color:#94A3B8">Rp {{ number_format((float)$b->total_transaksi,0,',','.') }}</span>
                        @else
                            Rp {{ number_format((float)$b->total_transaksi,0,',','.') }}
                        @endif
                    </td>
                    <td class="text-end" style="font-weight:700;color:#16A34A;white-space:nowrap">Rp {{ number_format((int)$b->nominal_masuk,0,',','.') }}</td>
                    <td class="text-end pe-3" style="font-weight:700;color:#D97706;white-space:nowrap">Rp {{ number_format((int)$b->sisa_tagihan,0,',','.') }}</td>
                    <td class="text-center pe-4">
                        <a href="{{ route('admin2.laporan.invoice', $b->booking_id) }}" class="inv-btn">
                            <i class="bi bi-receipt"></i> Invoice
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align:center;padding:56px;color:#94A3B8">
                        <i class="bi bi-folder2-open" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.35"></i>
                        Tidak ada data pada periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination ─────────────────────────────────────────── --}}
    <div class="pagi-wrap" id="pagiWrap"></div>

    {{-- Total Summary Footer ───────────────────────────────── --}}
    <div class="tbl-footer">
        <div class="tf-row">
            <div class="tf-row-label">
                <span class="tf-dot" style="background:#10B981"></span>
                Total Pendapatan Masuk (Lunas)
            </div>
            <div class="tf-row-value" style="color:#10B981">
                Rp {{ number_format($totalPendapatan,0,',','.') }}
            </div>
        </div>
        <div class="tf-row">
            <div class="tf-row-label">
                <span class="tf-dot" style="background:#F59E0B"></span>
                Total Masuk dari DP
            </div>
            <div class="tf-row-value" style="color:#D97706">
                Rp {{ number_format($totalPendapatanDp,0,',','.') }}
            </div>
        </div>
        <div class="tf-row">
            <div class="tf-row-label">
                <span class="tf-dot" style="background:#2563EB"></span>
                Total Estimasi Booking
            </div>
            <div class="tf-row-value" style="color:#2563EB">
                Rp {{ number_format($totalEstimasi,0,',','.') }}
            </div>
        </div>
        <div class="tf-row" style="background:#FFF9F0;border-top:1px solid #FDE68A">
            <div class="tf-row-label">
                <span class="tf-dot" style="background:#EF4444"></span>
                Total Sisa Tagihan
            </div>
            <div class="tf-row-value" style="color:#EF4444">
                Rp {{ number_format($totalSisa,0,',','.') }}
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
(function () {
    const rows      = Array.from(document.querySelectorAll('#tableBody .data-row'));
    const select    = document.getElementById('perPageSelect');
    const pagiWrap  = document.getElementById('pagiWrap');
    const tblInfo   = document.getElementById('tblInfo');
    const total     = rows.length;
    let perPage     = 10;
    let currentPage = 1;

    function totalPages() {
        return perPage === 'all' ? 1 : Math.ceil(total / perPage);
    }

    function render() {
        const tp = totalPages();
        // show/hide rows
        rows.forEach((r, i) => {
            if (perPage === 'all') {
                r.style.display = '';
            } else {
                const start = (currentPage - 1) * perPage;
                const end   = start + perPage;
                r.style.display = (i >= start && i < end) ? '' : 'none';
            }
        });

        // info text
        if (perPage === 'all') {
            tblInfo.textContent = `Menampilkan semua ${total} data`;
        } else {
            const from = Math.min((currentPage - 1) * perPage + 1, total);
            const to   = Math.min(currentPage * perPage, total);
            tblInfo.textContent = `Menampilkan ${from}–${to} dari ${total} data`;
        }

        // pagination
        buildPagi(tp);
    }

    function buildPagi(tp) {
        pagiWrap.innerHTML = '';
        if (tp <= 1) return;

        // prev
        const prev = makeBtn('‹ Prev', currentPage === 1);
        prev.addEventListener('click', () => { currentPage--; render(); });
        pagiWrap.appendChild(prev);

        // page numbers
        const pages = pageNumbers(currentPage, tp);
        pages.forEach(p => {
            if (p === '…') {
                const el = document.createElement('span');
                el.className = 'pagi-ellipsis';
                el.textContent = '…';
                pagiWrap.appendChild(el);
            } else {
                const btn = makeBtn(p, false, p === currentPage);
                btn.addEventListener('click', () => { currentPage = p; render(); });
                pagiWrap.appendChild(btn);
            }
        });

        // next
        const next = makeBtn('Next ›', currentPage === tp);
        next.addEventListener('click', () => { currentPage++; render(); });
        pagiWrap.appendChild(next);
    }

    function makeBtn(label, disabled, active) {
        const btn = document.createElement('button');
        btn.className = 'pagi-btn' + (active ? ' active' : '') + (disabled ? ' disabled' : '');
        btn.textContent = label;
        btn.disabled = disabled;
        return btn;
    }

    function pageNumbers(cur, total) {
        if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
        const pages = [];
        if (cur <= 4) {
            pages.push(1,2,3,4,5,'…',total);
        } else if (cur >= total - 3) {
            pages.push(1,'…',total-4,total-3,total-2,total-1,total);
        } else {
            pages.push(1,'…',cur-1,cur,cur+1,'…',total);
        }
        return pages;
    }

    select.addEventListener('change', () => {
        perPage     = select.value === 'all' ? 'all' : parseInt(select.value);
        currentPage = 1;
        render();
    });

    render();
})();
</script>
@endsection