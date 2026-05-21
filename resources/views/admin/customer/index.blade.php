@extends('layouts.admin2')
@section('title','Data Customer')
@section('hideTopbarTitle', '1')

@section('content')
<style>
@keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
@keyframes scaleIn{from{opacity:0;transform:scale(.93)}to{opacity:1;transform:scale(1)}}
.a1{animation:fadeUp .42s ease .04s both}.a2{animation:fadeUp .42s ease .10s both}
.a3{animation:fadeUp .42s ease .16s both}.a4{animation:fadeUp .42s ease .22s both}
.a5{animation:fadeUp .42s ease .28s both}

/* ── Hero ─────────────────────────────────── */
.cust-hero{
    background:linear-gradient(135deg,#0b1736 0%,#1a2f6a 60%,#0d2248 100%);
    border-radius:20px;padding:28px 32px;
    position:relative;overflow:hidden;margin-bottom:26px;
    box-shadow:0 20px 60px rgba(11,23,54,.22);
}
.cust-hero::before{
    content:'';position:absolute;top:-70px;right:-70px;
    width:280px;height:280px;border-radius:50%;
    background:radial-gradient(circle,rgba(212,169,106,.2) 0%,transparent 70%);
    pointer-events:none;
}
.cust-hero::after{
    content:'';position:absolute;bottom:-40px;left:200px;
    width:160px;height:160px;border-radius:50%;
    background:radial-gradient(circle,rgba(255,255,255,.04) 0%,transparent 70%);
    pointer-events:none;
}
.cust-hero-icon{
    width:52px;height:52px;border-radius:16px;
    background:rgba(212,169,106,.18);border:1px solid rgba(212,169,106,.3);
    display:flex;align-items:center;justify-content:center;
    font-size:1.4rem;color:#D4A96A;flex-shrink:0;
    box-shadow:0 8px 20px rgba(212,169,106,.2);
}

/* ── Stat cards ─────────────────────────────── */
.cust-stat{
    background:#fff;border-radius:16px;border:1px solid #E8EFF8;
    padding:20px 22px;position:relative;overflow:hidden;
    transition:transform .22s,box-shadow .22s;height:100%;
    box-shadow:0 4px 20px rgba(11,23,54,.06);
}
.cust-stat:hover{transform:translateY(-4px);box-shadow:0 14px 36px rgba(11,23,54,.12);}
.cust-stat::after{
    content:'';position:absolute;bottom:0;left:0;right:0;
    height:3px;border-radius:0 0 16px 16px;
    background:var(--sc,#E8EFF8);transition:height .2s;
}
.cust-stat:hover::after{height:4px;}
.cust-stat-deco{
    position:absolute;top:-20px;right:-20px;
    width:90px;height:90px;border-radius:50%;
    opacity:.06;background:var(--sc,#94A3B8);
}
.cust-stat-icon{
    width:44px;height:44px;border-radius:13px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;margin-bottom:14px;
}
.cust-stat-label{font-size:.67rem;text-transform:uppercase;letter-spacing:.1em;color:#94A3B8;font-weight:700;margin-bottom:4px;}
.cust-stat-value{font-size:1.75rem;font-weight:800;color:#0B1736;line-height:1;letter-spacing:-.03em;}

/* ── Search bar ─────────────────────────────── */
.cust-search-wrap{
    background:#fff;border-radius:16px;border:1px solid #E8EFF8;
    padding:20px 24px;margin-bottom:24px;
    box-shadow:0 4px 20px rgba(11,23,54,.05);
}
.cust-search-input-wrap{position:relative;}
.cust-search-input-wrap .search-icon{
    position:absolute;left:15px;top:50%;transform:translateY(-50%);
    color:#94A3B8;font-size:.95rem;pointer-events:none;
}
.cust-search-input{
    width:100%;height:46px;border:1.5px solid #E4EAF3;
    border-radius:12px;padding:0 16px 0 42px;
    font-size:.875rem;color:#1D2A44;background:#F8FAFF;
    transition:border-color .2s,box-shadow .2s,background .2s;
}
.cust-search-input:focus{
    outline:none;border-color:#0B1736;background:#fff;
    box-shadow:0 0 0 3px rgba(11,23,54,.07);
}
.cust-search-btn{
    height:46px;padding:0 24px;border:none;border-radius:12px;
    background:#0B1736;color:#fff;font-weight:700;font-size:.85rem;
    transition:opacity .2s,transform .15s;white-space:nowrap;
}
.cust-search-btn:hover{opacity:.85;transform:translateY(-1px);}
.cust-search-btn:active{transform:translateY(0);}

/* ── Table panel ─────────────────────────────── */
.cust-table-panel{
    background:#fff;border-radius:18px;border:1px solid #E8EFF8;
    overflow:hidden;box-shadow:0 6px 30px rgba(11,23,54,.07);
}

/* Top bar: show entries ──────────────────────── */
.tbl-top-bar{
    padding:14px 20px;border-bottom:1px solid #F1F5FB;
    display:flex;align-items:center;justify-content:space-between;
    flex-wrap:wrap;gap:10px;
    background:linear-gradient(180deg,#FAFCFF 0%,#F6F9FF 100%);
}
.tbl-top-left{
    display:flex;align-items:center;gap:8px;
    font-size:.78rem;color:#64748B;font-weight:500;
}
.show-select{
    border:1.5px solid #E4EAF3;border-radius:8px;
    font-size:.8rem;padding:5px 10px;color:#0B1736;
    font-weight:600;outline:none;cursor:pointer;
    transition:border-color .2s,box-shadow .2s;
    background:#fff;
}
.show-select:focus{border-color:#0B1736;box-shadow:0 0 0 3px rgba(11,23,54,.07);}
.tbl-top-right{font-size:.75rem;color:#94A3B8;font-weight:500;}
.tbl-count-pill{
    font-size:.72rem;background:#EEF3FF;color:#3451A0;
    border-radius:999px;padding:4px 12px;font-weight:700;
}

/* Table ──────────────────────────────────────── */
.cust-table{width:100%;border-collapse:collapse;}
.cust-table thead th{
    text-transform:uppercase;letter-spacing:.08em;font-size:.7rem;
    color:#7A8FA6;font-weight:700;
    background:linear-gradient(180deg,#F8FAFF 0%,#F3F7FF 100%);
    border-bottom:1px solid #E8EFF8;
    padding:14px 16px;white-space:nowrap;
}
.cust-table thead th:first-child{padding-left:24px;}
.cust-table thead th:last-child{padding-right:24px;text-align:right;}
.cust-table tbody td{
    padding:14px 16px;border-bottom:1px solid #F1F5FB;
    vertical-align:middle;font-size:.875rem;color:#1D2A44;
}
.cust-table tbody td:first-child{padding-left:24px;}
.cust-table tbody td:last-child{padding-right:24px;text-align:right;}
.cust-table tbody tr:last-child td{border-bottom:none;}
.cust-table tbody tr{transition:background .18s;}
.cust-table tbody tr:hover td{background:#F5F8FF;}

/* Row elements ───────────────────────────────── */
.cust-row-no{
    width:28px;height:28px;border-radius:8px;
    background:#F0F4FF;color:#5A72A0;
    font-size:.72rem;font-weight:700;
    display:inline-flex;align-items:center;justify-content:center;
}
.cust-avatar{
    width:42px;height:42px;border-radius:13px;
    background:linear-gradient(135deg,#0b1736 0%,#1d3461 100%);
    display:flex;align-items:center;justify-content:center;
    color:#D4A96A;font-weight:800;font-size:.88rem;flex-shrink:0;
    box-shadow:0 6px 14px rgba(11,23,54,.22);
    position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s;
}
.cust-avatar::before{
    content:'';position:absolute;inset:0;
    background:linear-gradient(135deg,rgba(212,169,106,.12) 0%,transparent 60%);
}
tr:hover .cust-avatar{transform:scale(1.06);box-shadow:0 8px 20px rgba(11,23,54,.3);}
.cust-name{font-weight:700;color:#1D2A44;font-size:.875rem;line-height:1.2;}
.cust-wa-link{
    display:inline-flex;align-items:center;gap:6px;
    color:#16A34A;font-weight:600;font-size:.82rem;text-decoration:none;
    padding:5px 11px;border-radius:8px;background:#F0FDF4;border:1px solid #BBF7D0;
    transition:all .2s;
}
.cust-wa-link:hover{background:#DCFCE7;color:#15803D;border-color:#86EFAC;transform:translateY(-1px);}
.cust-paket{
    font-size:.8rem;font-weight:600;color:#374151;background:#F3F4F6;
    border-radius:8px;padding:5px 10px;display:inline-block;
    max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.cust-termin{
    display:inline-flex;align-items:center;gap:6px;
    background:linear-gradient(135deg,#EFF6FF 0%,#DBEAFE 100%);
    color:#1D4ED8;border:1px solid #BFDBFE;border-radius:999px;
    padding:5px 13px;font-weight:700;font-size:.75rem;
}
.cust-termin-bar{width:40px;height:4px;background:#BFDBFE;border-radius:999px;overflow:hidden;margin-left:2px;}
.cust-termin-fill{height:100%;background:linear-gradient(90deg,#2563EB,#60A5FA);border-radius:999px;}
.cust-booking-badge{
    display:inline-flex;align-items:center;gap:5px;
    background:linear-gradient(135deg,#0B1736 0%,#1D3461 100%);
    color:#D4A96A;border-radius:999px;padding:6px 14px;
    font-weight:700;font-size:.76rem;box-shadow:0 4px 12px rgba(11,23,54,.2);
    transition:transform .2s,box-shadow .2s;
}
.cust-booking-badge:hover{transform:translateY(-1px);box-shadow:0 6px 16px rgba(11,23,54,.28);}
.cust-action-btn{
    width:36px;height:36px;display:inline-flex;
    align-items:center;justify-content:center;
    border-radius:10px;text-decoration:none;font-size:.9rem;transition:all .2s;
}
.cust-action-btn.view{background:#EEF3FF;color:#2563EB;border:1px solid #DBEAFE;}
.cust-action-btn.view:hover{
    background:#2563EB;color:#fff;border-color:#2563EB;
    transform:translateY(-2px);box-shadow:0 6px 14px rgba(37,99,235,.3);
}

/* empty state ────────────────────────────────── */
.cust-empty{padding:60px 20px;text-align:center;}
.cust-empty-icon{
    width:80px;height:80px;border-radius:22px;background:#F0F4FF;
    display:flex;align-items:center;justify-content:center;
    font-size:2rem;color:#94A3B8;margin:0 auto 16px;
}

/* ── Pagination ───────────────────────────────── */
.cust-pagination .pagination{margin:0;gap:5px;}
.cust-pagination .page-link{
    border:1.5px solid #E4EAF3;color:#27406a;border-radius:10px;
    min-width:40px;text-align:center;font-weight:600;font-size:.82rem;
    padding:8px 12px;transition:all .18s;
}
.cust-pagination .page-link:hover{background:#EEF3FF;border-color:#BFDBFE;color:#1D4ED8;}
.cust-pagination .page-item.active .page-link{
    background:linear-gradient(135deg,#0B1736 0%,#1D3461 100%);
    border-color:#0B1736;color:#D4A96A;
    box-shadow:0 4px 12px rgba(11,23,54,.3);
}
.cust-pagination .page-item.disabled .page-link{color:#CBD5E1;background:#F8FAFF;border-color:#E8EFF8;}
</style>

{{-- ── HERO ─────────────────────────────────────────── --}}
<div class="cust-hero a1">
    <div class="d-flex align-items-center gap-4" style="position:relative;z-index:1">
        <div class="cust-hero-icon"><i class="bi bi-people-fill"></i></div>
        <div class="flex-1">
            <div style="font-size:.6rem;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.38);margin-bottom:5px">Admin Panel · Manajemen</div>
            <h4 style="font-size:1.45rem;font-weight:800;color:#fff;margin:0;letter-spacing:-.02em">Data Customer</h4>
            <p style="font-size:.83rem;color:rgba(255,255,255,.45);margin:4px 0 0">Daftar semua client yang pernah booking di Enamorapic</p>
        </div>
        <div class="d-none d-lg-flex gap-3 ms-auto">
            <div style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:13px 22px;text-align:center;min-width:110px;">
                <div style="font-size:1.4rem;font-weight:800;color:#fff;line-height:1">{{ number_format($stats['totalCustomers'] ?? 0) }}</div>
                <div style="font-size:.62rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.1em;margin-top:3px">Total</div>
            </div>
            <div style="background:rgba(212,169,106,.15);border:1px solid rgba(212,169,106,.28);border-radius:14px;padding:13px 22px;text-align:center;min-width:110px;">
                <div style="font-size:1.4rem;font-weight:800;color:#D4A96A;line-height:1">{{ number_format($stats['totalBookings'] ?? 0) }}</div>
                <div style="font-size:.62rem;color:rgba(255,255,255,.38);text-transform:uppercase;letter-spacing:.1em;margin-top:3px">Booking</div>
            </div>
        </div>
    </div>
</div>

{{-- ── STAT CARDS ───────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 a2">
        <div class="cust-stat" style="--sc:#2563EB">
            <div class="cust-stat-deco"></div>
            <div class="cust-stat-icon" style="background:#EFF6FF;color:#2563EB"><i class="bi bi-people-fill"></i></div>
            <div class="cust-stat-label">Total Customer</div>
            <div class="cust-stat-value">{{ number_format($stats['totalCustomers'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 a2" style="animation-delay:.06s">
        <div class="cust-stat" style="--sc:#8B5CF6">
            <div class="cust-stat-deco"></div>
            <div class="cust-stat-icon" style="background:#F5F3FF;color:#8B5CF6"><i class="bi bi-calendar2-check-fill"></i></div>
            <div class="cust-stat-label">Total Booking</div>
            <div class="cust-stat-value" style="color:#8B5CF6">{{ number_format($stats['totalBookings'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 a2" style="animation-delay:.12s">
        <div class="cust-stat" style="--sc:#D97706">
            <div class="cust-stat-deco"></div>
            <div class="cust-stat-icon" style="background:#FFFBEB;color:#D97706"><i class="bi bi-wallet2"></i></div>
            <div class="cust-stat-label">Customer DP</div>
            <div class="cust-stat-value" style="color:#D97706">{{ number_format($stats['dpCustomers'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 a2" style="animation-delay:.18s">
        <div class="cust-stat" style="--sc:#10B981">
            <div class="cust-stat-deco"></div>
            <div class="cust-stat-icon" style="background:#ECFDF5;color:#10B981"><i class="bi bi-patch-check-fill"></i></div>
            <div class="cust-stat-label">Customer Lunas</div>
            <div class="cust-stat-value" style="color:#10B981">{{ number_format($stats['lunasCustomers'] ?? 0) }}</div>
        </div>
    </div>
</div>

{{-- ── SEARCH ───────────────────────────────────────── --}}
<div class="cust-search-wrap a3">
    <form method="GET" id="searchForm">
        {{-- Simpan perPage agar tidak hilang saat search --}}
        <input type="hidden" name="per_page" id="hiddenPerPage" value="{{ request('per_page', 10) }}">
        <div class="row g-2 align-items-end">
            <div class="col">
                <label style="font-size:.68rem;text-transform:uppercase;letter-spacing:.08em;color:#64748B;font-weight:700;margin-bottom:6px;display:block">Cari Customer</label>
                <div class="cust-search-input-wrap">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" class="cust-search-input"
                           value="{{ request('search') }}"
                           placeholder="Nama client atau nomor WhatsApp...">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="cust-search-btn">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
            </div>
            @if(request('search'))
            <div class="col-auto">
                <a href="{{ route('admin2.customer.index') }}"
                   style="height:46px;display:inline-flex;align-items:center;padding:0 16px;border-radius:12px;border:1.5px solid #E4EAF3;background:#fff;color:#64748B;font-size:.85rem;font-weight:600;text-decoration:none;transition:all .2s;"
                   onmouseover="this.style.background='#F8FAFF'" onmouseout="this.style.background='#fff'">
                    <i class="bi bi-x-lg me-1"></i> Reset
                </a>
            </div>
            @endif
        </div>
    </form>
</div>

{{-- ── TABLE ────────────────────────────────────────── --}}
<div class="cust-table-panel a4">

    {{-- Top bar: show entries ──────────────────────── --}}
    <div class="tbl-top-bar">
        <div class="tbl-top-left">
            Tampilkan
            <select class="show-select" id="perPageSelect" onchange="changePerPage(this.value)">
                @foreach([10, 20, 30, 50, 100] as $opt)
                <option value="{{ $opt }}" {{ request('per_page', 10) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
            data per halaman
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="tbl-top-right">
                @php
                    $from = $customers->firstItem() ?? 0;
                    $to   = $customers->lastItem()  ?? 0;
                    $tot  = $customers->total();
                @endphp
                Menampilkan {{ $from }}–{{ $to }} dari {{ $tot }} data
            </span>
            <span class="tbl-count-pill">{{ $tot }} total</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="cust-table">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama Client</th>
                    <th>No. WhatsApp</th>
                    <th>Paket Terakhir</th>
                    <th>Progress Termin</th>
                    <th>Total Booking</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $no => $c)
                @php
                    $wa = preg_replace('/[^0-9]/','', $c->no_wa ?? '');
                    if(substr($wa,0,1)=='0') $wa='62'.substr($wa,1);
                    $linkWA        = "https://wa.me/{$wa}";
                    $latestBooking = $c->bookings->first();
                    $latestPaket   = $latestBooking?->details->first()?->paket?->nama_paket ?? '-';
                    $totalTerms    = $latestBooking?->paymentTerms?->count() ?? 0;
                    $paidTerms     = $latestBooking?->paymentTerms?->where('payment_status','paid')->count() ?? 0;
                    $terminPct     = $totalTerms > 0 ? round(($paidTerms / $totalTerms) * 100) : 0;
                    $rowNo         = ($customers->currentPage() - 1) * $customers->perPage() + $no + 1;
                @endphp
                <tr>
                    <td><span class="cust-row-no">{{ $rowNo }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="cust-avatar">{{ strtoupper(substr($c->nama_client, 0, 2)) }}</div>
                            <span class="cust-name">{{ $c->nama_client }}</span>
                        </div>
                    </td>
                    <td>
                        <a href="{{ $linkWA }}" target="_blank" class="cust-wa-link">
                            <i class="bi bi-whatsapp"></i>{{ $c->no_wa }}
                        </a>
                    </td>
                    <td>
                        @if($latestPaket !== '-')
                            <span class="cust-paket">{{ $latestPaket }}</span>
                        @else
                            <span style="color:#CBD5E1">—</span>
                        @endif
                    </td>
                    <td>
                        @if($totalTerms > 0)
                            <div class="d-flex align-items-center gap-2">
                                <span class="cust-termin">
                                    <i class="bi bi-receipt" style="font-size:.7rem"></i>
                                    {{ $paidTerms }}/{{ $totalTerms }}
                                </span>
                                <div class="cust-termin-bar">
                                    <div class="cust-termin-fill" style="width:{{ $terminPct }}%"></div>
                                </div>
                            </div>
                        @else
                            <span style="color:#CBD5E1;font-size:.82rem">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="cust-booking-badge">
                            <i class="bi bi-calendar2-check" style="font-size:.78rem"></i>
                            {{ $c->bookings_count }} Booking
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin2.customer.show', $c->customer_id) }}"
                           class="cust-action-btn view" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="cust-empty">
                            <div class="cust-empty-icon"><i class="bi bi-people"></i></div>
                            <div style="font-size:.95rem;font-weight:700;color:#1D2A44;margin-bottom:6px">Belum ada data customer</div>
                            <div style="font-size:.82rem;color:#94A3B8">
                                @if(request('search'))
                                    Tidak ada customer yang cocok dengan "<strong>{{ request('search') }}</strong>"
                                @else
                                    Data customer akan muncul setelah ada booking masuk
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination ──────────────────────────────────── --}}
    @if($customers->hasPages())
    <div style="padding:14px 20px;border-top:1px solid #F1F5FB;display:flex;justify-content:center;">
        <div class="cust-pagination">
            {{ $customers->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
function changePerPage(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', val);
    url.searchParams.set('page', 1); // reset ke halaman 1
    window.location.href = url.toString();
}
</script>
@endsection