<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Dashboard') — Enamorapic</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ─── BASE ───────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; }
:root {
    --navy:     #0B1736;
    --gold:     #D4A96A;
    --bg:       #F0F3F9;
    --white:    #FFFFFF;
    --slate:    #64748B;
    --border:   #E4EAF3;
    --sw:       280px;
    --hh:       66px;
    --r:        14px;
    --sh:       0 2px 16px rgba(11,23,54,.07);
    --sh2:      0 8px 32px rgba(11,23,54,.13);
}
html,body { height:100%; }
body {
    font-family:'Inter',sans-serif;
    background:var(--bg);
    color:#1D2A44;
    opacity:0;
    transition:opacity .26s ease;
    -webkit-font-smoothing:antialiased;
    overflow-x:hidden;
}
body.ready   { opacity:1; }
body.leaving { opacity:0; pointer-events:none; }
/* SweetAlert2 */
.swal2-popup { border-radius: 16px; padding: 22px 20px; }
.swal2-title { font-size: 1.15rem; font-weight: 700; color: #1a2332; }
.swal2-html-container { color: #6b7a96; font-size: .92rem; }
.swal2-confirm, .swal2-cancel { border-radius: 10px; padding: 8px 18px; font-weight: 600; }
.swal2-toast { border-radius: 12px; padding: 10px 14px; box-shadow: 0 12px 30px rgba(11,23,54,.12); }
.swal2-toast .swal2-title { font-size: .86rem; font-weight: 600; color: #1a2332; }
.toast-slide-in { animation: toast-in .35s ease; }
.toast-slide-out { animation: toast-out .25s ease forwards; }
.swal2-animate-in { animation: swal-in .28s ease; }
.swal2-animate-out { animation: swal-out .2s ease forwards; }
@keyframes toast-in { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: translateX(0); } }
@keyframes toast-out { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(24px); } }
@keyframes swal-in { from { opacity: 0; transform: translateY(8px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes swal-out { from { opacity: 1; transform: translateY(0) scale(1); } to { opacity: 0; transform: translateY(8px) scale(.98); } }

/* ─── SIDEBAR ─────────────────────────────── */
.sidebar {
    position:fixed;
    top:0;left:0;bottom:0;
    width:var(--sw);
    background:var(--navy);
    z-index:1000;
    display:flex;
    flex-direction:column;
    transition:transform .32s cubic-bezier(.4,0,.2,1);
    overflow:hidden;
}
/* decorative blobs */
.sidebar::before {
    content:'';
    position:absolute;
    top:-80px;right:-80px;
    width:260px;height:260px;
    border-radius:50%;
    background:radial-gradient(circle,rgba(212,169,106,.15) 0%,transparent 70%);
    pointer-events:none;
}
.sidebar::after {
    content:'';
    position:absolute;
    bottom:60px;left:-60px;
    width:180px;height:180px;
    border-radius:50%;
    background:radial-gradient(circle,rgba(255,255,255,.04) 0%,transparent 70%);
    pointer-events:none;
}

/* brand */
.sb-brand {
    padding:24px 22px 20px;
    border-bottom:1px solid rgba(255,255,255,.07);
    flex-shrink:0;
}
.sb-brand img {
    height:36px;
    filter:brightness(0) invert(1);
    opacity:.92;
}
.sb-role-pill {
    display:inline-flex;
    align-items:center;
    gap:5px;
    margin-top:9px;
    background:rgba(212,169,106,.15);
    border:1px solid rgba(212,169,106,.25);
    border-radius:999px;
    padding:3px 10px;
    font-size:.62rem;
    letter-spacing:.18em;
    text-transform:uppercase;
    color:var(--gold);
    font-weight:700;
}
.sb-role-dot {
    width:5px;height:5px;
    border-radius:50%;
    background:var(--gold);
    box-shadow:0 0 4px var(--gold);
}

/* nav */
.sb-nav {
    flex:1;
    overflow-y:auto;
    padding:16px 14px;
    scrollbar-width:none;
}
.sb-nav::-webkit-scrollbar { display:none; }

.sb-section {
    font-size:.6rem;
    letter-spacing:.2em;
    text-transform:uppercase;
    color:rgba(255,255,255,.25);
    padding:16px 10px 7px;
    font-weight:700;
}

.sb-link {
    display:flex;
    align-items:center;
    gap:12px;
    padding:11px 13px;
    border-radius:11px;
    color:rgba(255,255,255,.58);
    text-decoration:none;
    font-size:.875rem;
    font-weight:500;
    transition:all .2s;
    margin-bottom:2px;
    position:relative;
}
.sb-link i {
    font-size:1rem;
    width:20px;
    text-align:center;
    flex-shrink:0;
    transition:transform .2s;
}
.sb-link:hover {
    color:#fff;
    background:rgba(255,255,255,.08);
}
.sb-link:hover i { transform:scale(1.1); }
.sb-link.active {
    color:#fff;
    background:rgba(212,169,106,.16);
}
.sb-link.active::before {
    content:'';
    position:absolute;
    left:0;top:22%;bottom:22%;
    width:3px;
    border-radius:0 3px 3px 0;
    background:var(--gold);
}
.sb-badge {
    margin-left:auto;
    background:#EF4444;
    color:#fff;
    font-size:.63rem;
    font-weight:700;
    padding:2px 8px;
    border-radius:999px;
    line-height:1.5;
    animation:sbPulse 2s ease infinite;
}
@keyframes sbPulse { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)} 50%{box-shadow:0 0 0 4px rgba(239,68,68,0)} }

/* user card */
.sb-user {
    flex-shrink:0;
    padding:14px 16px;
    border-top:1px solid rgba(255,255,255,.07);
    display:flex;
    align-items:center;
    gap:12px;
    background:rgba(0,0,0,.12);
}
.sb-avatar {
    width:38px;height:38px;
    border-radius:11px;
    background:var(--gold);
    display:flex;align-items:center;justify-content:center;
    font-weight:800;font-size:.82rem;
    color:var(--navy);
    flex-shrink:0;
    box-shadow:0 2px 8px rgba(212,169,106,.4);
    overflow:hidden;
}
.avatar-media {
    width:100%;
    height:100%;
    object-fit:cover;
}
.sb-user-info { flex:1;min-width:0; }
.sb-user-name {
    font-size:.83rem;
    font-weight:700;
    color:#fff;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}
.sb-user-role {
    font-size:.66rem;
    color:rgba(255,255,255,.38);
    text-transform:uppercase;
    letter-spacing:.1em;
    margin-top:1px;
}
.sb-user-note {
    font-size:.63rem;
    color:rgba(255,255,255,.45);
    letter-spacing:.02em;
    margin-top:2px;
}

/* ─── MAIN ─────────────────────────────────── */
.main-wrap {
    margin-left:var(--sw);
    min-height:100vh;
    display:flex;
    flex-direction:column;
    transition:margin-left .32s cubic-bezier(.4,0,.2,1);
}

/* topbar */
.topbar {
    min-height:var(--hh);
    background:var(--white);
    border-bottom:1px solid var(--border);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:11px 24px;
    position:sticky;
    top:0;
    z-index:100;
    box-shadow:0 1px 0 var(--border);
    gap:16px;
    flex-wrap:wrap;
}
.topbar-left { display:flex;align-items:center;gap:14px; }
.topbar-center {
    flex:1;
    min-width:220px;
    max-width:560px;
}
.top-search {
    position:relative;
}
.top-search .bi-search {
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#7f8fab;
    font-size:.92rem;
}
.top-search-input {
    width:100%;
    border:1px solid #d8e1ef;
    background:linear-gradient(180deg,#f9fbff 0%,#f3f7ff 100%);
    border-radius:12px;
    height:42px;
    padding:0 14px 0 38px;
    font-size:.86rem;
    color:#1d2a44;
    transition:border-color .2s, box-shadow .2s;
}
.top-search-input:focus {
    outline:none;
    border-color:#2a4e9e;
    box-shadow:0 0 0 4px rgba(42,78,158,.12);
}
.hamburger {
    display:none;
    width:40px;height:40px;
    border-radius:11px;
    border:1.5px solid var(--border);
    background:var(--white);
    align-items:center;justify-content:center;
    cursor:pointer;
    color:var(--navy);
    font-size:1.15rem;
    transition:all .2s;
}
.hamburger:hover { background:var(--navy);color:#fff;border-color:var(--navy); }
.topbar-label .pn {
    font-size:1rem;
    font-weight:700;
    color:var(--navy);
    line-height:1.2;
}
.topbar-label .ps {
    font-size:.68rem;
    color:var(--slate);
    letter-spacing:.1em;
    text-transform:uppercase;
}
.topbar-avatar {
    width:38px;height:38px;
    border-radius:11px;
    background:var(--navy);
    color:#fff;
    display:flex;align-items:center;justify-content:center;
    font-weight:700;font-size:.8rem;
    box-shadow:0 2px 8px rgba(11,23,54,.2);
    overflow:hidden;
}
.topbar-right {
    display:flex;
    align-items:center;
    gap:10px;
}
.top-action {
    width:40px;
    height:40px;
    border-radius:12px;
    border:1px solid var(--border);
    background:#fff;
    color:#3e4f72;
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    transition:all .2s;
}
.top-action:hover {
    border-color:#cdd9ee;
    background:#f7faff;
    color:#1f3568;
}
.top-action-badge {
    position:absolute;
    right:-3px;
    top:-4px;
    min-width:17px;
    height:17px;
    border-radius:999px;
    background:#ef4444;
    color:#fff;
    font-size:.63rem;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    border:2px solid #fff;
    padding:0 4px;
}
.notif-menu,
.account-menu {
    width:min(360px, calc(100vw - 24px));
    border-radius:14px;
    border:1px solid #e6ecf8;
    box-shadow:0 20px 46px rgba(11,23,54,.14);
    padding:0;
    overflow:hidden;
}
.notif-head {
    padding:12px 14px;
    background:#f6f9ff;
    border-bottom:1px solid #e7edf8;
}
.notif-item {
    padding:11px 14px;
    border-bottom:1px solid #eef2fa;
    text-decoration:none;
    display:flex;
    align-items:flex-start;
    gap:10px;
    color:#1d2a44;
    transition:background .18s;
}
.notif-item:hover { background:#f8fbff; }
.notif-list {
    max-height:346px;
    overflow-y:auto;
    overscroll-behavior:contain;
    scroll-behavior:smooth;
}
.notif-section {
    border-bottom:1px solid #edf2fb;
}
.notif-section:last-child {
    border-bottom:none;
}
.notif-section-title {
    position:sticky;
    top:0;
    z-index:1;
    background:#f9fbff;
    border-bottom:1px solid #edf2fb;
    padding:7px 14px;
    font-size:.68rem;
    letter-spacing:.08em;
    text-transform:uppercase;
    color:#6b7d9a;
    font-weight:700;
}
.notif-list::-webkit-scrollbar { width:8px; }
.notif-list::-webkit-scrollbar-thumb {
    background:#d4deef;
    border-radius:999px;
    border:2px solid #f7faff;
}
.notif-icon {
    width:30px;
    height:30px;
    border-radius:9px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:.76rem;
    flex-shrink:0;
}
.notif-icon.website { background:#e9f2ff; color:#1f5fbf; }
.notif-icon.booking { background:#fff4e5; color:#9a5a00; }
.notif-content { min-width:0; }
.notif-title {
    font-size:.8rem;
    font-weight:700;
    line-height:1.2;
}
.notif-sub {
    font-size:.76rem;
    color:#546582;
    margin-top:1px;
}
.notif-time {
    font-size:.7rem;
    color:#8da0bf;
    margin-top:2px;
}
.account-head {
    padding:14px;
    background:linear-gradient(120deg,#0b1736 0%,#122755 100%);
    color:#fff;
}
.account-row {
    display:flex;
    align-items:center;
    gap:10px;
}
.account-avatar {
    width:46px;
    height:46px;
    border-radius:14px;
    background:var(--gold);
    color:#0b1736;
    font-weight:800;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}
.account-form {
    padding:12px 14px 14px;
}
.account-label {
    font-size:.7rem;
    color:#7083a5;
    letter-spacing:.08em;
    text-transform:uppercase;
    font-weight:700;
    margin-bottom:5px;
}
.account-input {
    width:100%;
    border:1px solid #dde6f5;
    border-radius:10px;
    height:40px;
    padding:0 12px;
    font-size:.84rem;
}
.account-file {
    width:100%;
    border:1px dashed #cfd9ea;
    border-radius:10px;
    padding:8px;
    font-size:.8rem;
    background:#fafcff;
}
.account-save {
    width:100%;
    border:none;
    border-radius:10px;
    background:#14306d;
    color:#fff;
    font-weight:700;
    font-size:.82rem;
    padding:9px 12px;
    margin-top:10px;
}
.account-logout {
    display:block;
    width:100%;
    border:none;
    border-top:1px solid #edf1f9;
    background:#fff8f8;
    color:#b42318;
    font-size:.82rem;
    font-weight:700;
    padding:10px 12px;
}

.top-account-trigger {
    border:1px solid var(--border);
    background:#fff;
    border-radius:12px;
    padding:3px 7px 3px 3px;
    display:flex;
    align-items:center;
    gap:7px;
    color:#1d2a44;
}
.top-account-name {
    font-size:.8rem;
    font-weight:700;
    max-width:112px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

/* page body */
.page-body {
    flex:1;
    padding:28px 30px;
    animation:pbIn .38s ease both;
}
@keyframes pbIn {
    from{opacity:0;transform:translateY(8px)}
    to  {opacity:1;transform:translateY(0)}
}

/* util */
.card-clean {
    background:var(--white);
    border-radius:var(--r);
    border:1px solid var(--border);
    box-shadow:var(--sh);
}
.alert { border-radius:var(--r);border:none;font-size:.875rem; }

/* ─── OVERLAY MOBILE ──────────────────────── */
.sb-overlay {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(11,23,54,.45);
    z-index:999;
    backdrop-filter:blur(3px);
}

/* ─── RESPONSIVE ──────────────────────────── */
@media(max-width:991px){
    .sidebar{transform:translateX(-100%)}
    .sidebar.open{transform:translateX(0)}
    .main-wrap{margin-left:0}
    .hamburger{display:flex}
    .sb-overlay{display:block;opacity:0;pointer-events:none;transition:opacity .3s}
    .sb-overlay.open{opacity:1;pointer-events:auto}
    .page-body{padding:18px}
    .topbar{padding:10px 14px}
    .topbar-center{order:3;max-width:100%;flex-basis:100%}
    .topbar-right{margin-left:auto}
}
@media(max-width:575px){
    .topbar-label .pn{font-size:.92rem}
    .topbar-label .ps{font-size:.63rem}
    .top-account-name{display:none}
}
</style>
@yield('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">

    <div class="sb-brand">
        <img src="{{ asset('assets/images/enamora.png') }}" alt="Enamorapic">
        <div class="sb-role-pill">
            <span class="sb-role-dot"></span>
            {{ Auth::user()->role ?? 'Admin' }} Panel
        </div>
    </div>

    <nav class="sb-nav">
        <div class="sb-section">Main</div>

        <a href="{{ route('admin.dashboard') }}"
           class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.pesanan') }}"
           class="sb-link {{ request()->routeIs('admin.pesanan') ? 'active' : '' }}">
            <i class="bi bi-calendar2-check-fill"></i> Data Pesanan
        </a>
        <a href="{{ route('admin.pesanan-website') }}"
           class="sb-link {{ request()->routeIs('admin.pesanan-website*') ? 'active' : '' }}">
            <i class="bi bi-bell-fill"></i> Pesanan Website
            @php
                try { $pendingCount = \App\Models\PesananWebsite::where('status','pending')->count(); }
                catch(\Exception $e) { $pendingCount = 0; }
            @endphp
            @if($pendingCount > 0)
                <span class="sb-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <div class="sb-section">Manajemen</div>

        <a href="{{ route('admin.paket.index') }}"
           class="sb-link {{ request()->routeIs('admin.paket*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i> Paket Foto
        </a>
        <a href="{{ route('admin.customer.index') }}"
           class="sb-link {{ request()->routeIs('admin.customer*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Customer
        </a>
        <a href="{{ route('admin.laporan') }}"
           class="sb-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line-fill"></i> Laporan
        </a>

        <div class="sb-section">Lainnya</div>

        <a href="{{ route('home') }}" target="_blank" class="sb-link">
            <i class="bi bi-globe2"></i> Lihat Website
        </a>
    </nav>

    <div class="sb-user">
        <div class="sb-avatar">
            @if(Auth::user()?->profile_photo_url)
                <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="avatar-media">
            @else
                {{ Auth::user()->avatar_initial ?? 'A' }}
            @endif
        </div>
        <div class="sb-user-info">
            <div class="sb-user-name">{{ Auth::user()->nama_lengkap ?? 'Admin' }}</div>
            <div class="sb-user-role">{{ Auth::user()->role ?? '' }}</div>
            <div class="sb-user-note">Kelola akun di menu profil atas</div>
        </div>
    </div>
</aside>

<div class="sb-overlay" id="sbOverlay" onclick="closeSidebar()"></div>

{{-- MAIN --}}
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-label">
                <div class="ps">Enamorapic Studio</div>
                <div class="pn">@yield('title','Dashboard')</div>
            </div>
        </div>

        <div class="topbar-center">
            <form method="GET" action="{{ route('admin.search') }}" class="top-search">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    name="q"
                    class="top-search-input"
                    placeholder="Cari booking, customer, nomor WA, atau pesanan website..."
                    value="{{ request('q') }}"
                >
            </form>
        </div>

        <div class="topbar-right">
            <div class="dropdown">
                <button class="top-action" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Notifikasi">
                    <i class="bi bi-bell-fill"></i>
                    @php $notifCount = (int) (($topbarNotifItems ?? collect())->count()); @endphp
                    @if($notifCount > 0)
                        <span class="top-action-badge">{{ $notifCount > 99 ? '99+' : $notifCount }}</span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end notif-menu">
                    <div class="notif-head">
                        <div style="font-weight:700;color:#0b1736;">Ringkasan Pesanan Masuk</div>
                        <div style="font-size:.75rem;color:#657b9c;">
                            Website pending: <strong>{{ $pendingWebsiteCount ?? 0 }}</strong> •
                            Booking pending: <strong>{{ $pendingBookingCount ?? 0 }}</strong> •
                            Booking hari ini: <strong>{{ $newBookingTodayCount ?? 0 }}</strong>
                        </div>
                    </div>

                    <div class="notif-list">
                        @php
                            $notifItems = $topbarNotifItems ?? collect();
                            $notifGroups = [
                                'website' => 'Pesanan Website',
                                'booking' => 'Booking Internal',
                            ];
                        @endphp

                        @if($notifItems->isEmpty())
                            <div class="notif-item" style="cursor:default;">
                                <div class="notif-content">
                                    <div class="notif-sub">Belum ada notifikasi terbaru.</div>
                                </div>
                            </div>
                        @else
                            @foreach($notifGroups as $type => $label)
                                @php $groupItems = $notifItems->where('type', $type)->values(); @endphp
                                @if($groupItems->isNotEmpty())
                                    <div class="notif-section">
                                        <div class="notif-section-title">{{ $label }} ({{ $groupItems->count() }})</div>
                                        @foreach($groupItems as $item)
                                            <a href="{{ $item['url'] }}" class="notif-item">
                                                <div class="notif-icon {{ $item['type'] ?? 'booking' }}">
                                                    @if(($item['type'] ?? '') === 'website')
                                                        <i class="bi bi-globe2"></i>
                                                    @else
                                                        <i class="bi bi-calendar2-check"></i>
                                                    @endif
                                                </div>
                                                <div class="notif-content">
                                                    <div class="notif-title">{{ $item['title'] }}</div>
                                                    <div class="notif-sub">{{ $item['subtitle'] }}</div>
                                                    <div class="notif-time">{{ $item['time'] }}</div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="dropdown">
                <button class="top-account-trigger" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Akun CEO">
                    <div class="topbar-avatar">
                        @if(Auth::user()?->profile_photo_url)
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="avatar-media">
                        @else
                            {{ Auth::user()->avatar_initial ?? 'A' }}
                        @endif
                    </div>
                    <span class="top-account-name">{{ Auth::user()->nama_lengkap ?? 'CEO' }}</span>
                    <i class="bi bi-chevron-down" style="font-size:.75rem;color:#6f809f;"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end account-menu">
                    <div class="account-head">
                        <div class="account-row">
                            <div class="account-avatar">
                                @if(Auth::user()?->profile_photo_url)
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="avatar-media">
                                @else
                                    {{ Auth::user()->avatar_initial ?? 'A' }}
                                @endif
                            </div>
                            <div>
                                <div style="font-size:.86rem;font-weight:700;line-height:1.2;">{{ Auth::user()->nama_lengkap ?? 'CEO' }}</div>
                                <div style="font-size:.72rem;color:rgba(255,255,255,.72);">Login sebagai {{ Auth::user()->role ?? 'CEO' }}</div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="account-form">
                        @csrf
                        <label class="account-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->nama_lengkap) }}" class="account-input" required>

                        <label class="account-label" style="margin-top:10px;">Ganti Foto Profil</label>
                        <input type="file" name="profile_photo" accept="image/png,image/jpeg,image/jpg,image/webp" class="account-file">

                        <button type="submit" class="account-save">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Profil
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="account-logout">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="page-body">
        @if(session('success'))
        <script>
            window.__flashQueue = window.__flashQueue || [];
            window.__flashQueue.push({ icon: 'success', title: @json(session('success')) });
        </script>
        @endif
        @if(session('error'))
        <script>
            window.__flashQueue = window.__flashQueue || [];
            window.__flashQueue.push({ icon: 'error', title: @json(session('error')) });
        </script>
        @endif
        @if(session('warning'))
        <script>
            window.__flashQueue = window.__flashQueue || [];
            window.__flashQueue.push({ icon: 'warning', title: @json(session('warning')) });
        </script>
        @endif
        @if(session('info'))
        <script>
            window.__flashQueue = window.__flashQueue || [];
            window.__flashQueue.push({ icon: 'info', title: @json(session('info')) });
        </script>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sbOverlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sbOverlay').classList.remove('open');
}
(function(){
    const D=220;
    function rdy(){document.body.classList.add('ready');}
    window.addEventListener('load',rdy);
    window.addEventListener('pageshow',rdy);
    document.addEventListener('click',function(e){
        const a=e.target.closest('a[href]');
        if(!a)return;
        const h=a.getAttribute('href')||'';
        if(!h||h.startsWith('#')||h.startsWith('javascript:'))return;
        if(a.target==='_blank'||a.hasAttribute('download'))return;
        if(e.metaKey||e.ctrlKey||e.shiftKey||e.altKey)return;
        let n;try{n=new URL(a.href,location.href);}catch{return;}
        if(n.origin!==location.origin||n.href===location.href)return;
        e.preventDefault();
        document.body.classList.add('leaving');
        setTimeout(()=>{location.href=n.href;},D);
    });
})();
</script>
<script>
(() => {
    const flashQueue = window.__flashQueue || [];
    if (window.Swal && flashQueue.length) {
        flashQueue.forEach((item) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: item.icon || 'info',
                title: item.title || '',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                showClass: { popup: 'toast-slide-in' },
                hideClass: { popup: 'toast-slide-out' },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        });
    }

    const buildConfirmOptions = (dataset) => {
        const type = (dataset.confirmType || '').toLowerCase();
        const isDelete = type === 'delete';
        return {
            title: dataset.confirmTitle || (isDelete ? 'Hapus Data' : 'Konfirmasi'),
            text: dataset.confirmText || dataset.confirm || 'Lanjutkan proses ini?',
            icon: dataset.confirmIcon || (isDelete ? 'warning' : 'question'),
            confirmButtonText: dataset.confirmButton || (isDelete ? 'Ya, hapus' : 'Ya, lanjutkan'),
            cancelButtonText: dataset.confirmCancel || 'Batal',
            confirmButtonColor: dataset.confirmColor || (isDelete ? '#ef4444' : '#1f6fd1')
        };
    };

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!form) return;

        const submitter = event.submitter;
        const data = Object.assign({}, form.dataset, submitter ? submitter.dataset : {});
        if (!data.confirm && !data.confirmText) return;

        event.preventDefault();
        const options = buildConfirmOptions(data);

        Swal.fire({
            title: options.title,
            text: options.text,
            icon: options.icon,
            showCancelButton: true,
            confirmButtonText: options.confirmButtonText,
            cancelButtonText: options.cancelButtonText,
            reverseButtons: true,
            focusCancel: true,
            confirmButtonColor: options.confirmButtonColor,
            cancelButtonColor: '#9aa0a6',
            showClass: { popup: 'swal2-animate-in' },
            hideClass: { popup: 'swal2-animate-out' }
        }).then((result) => {
            if (result.isConfirmed) {
                if (submitter) submitter.disabled = true;
                form.submit();
            }
        });
    });

    document.addEventListener('click', (event) => {
        const link = event.target.closest('[data-confirm-link]');
        if (!link) return;

        event.preventDefault();
        const options = buildConfirmOptions(link.dataset);

        Swal.fire({
            title: options.title,
            text: options.text,
            icon: options.icon,
            showCancelButton: true,
            confirmButtonText: options.confirmButtonText,
            cancelButtonText: options.cancelButtonText,
            reverseButtons: true,
            focusCancel: true,
            confirmButtonColor: options.confirmButtonColor,
            cancelButtonColor: '#9aa0a6',
            showClass: { popup: 'swal2-animate-in' },
            hideClass: { popup: 'swal2-animate-out' }
        }).then((result) => {
            if (result.isConfirmed) {
                const href = link.getAttribute('href');
                if (href) window.location.href = href;
            }
        });
    });
})();
</script>
@yield('scripts')
</body>
</html>