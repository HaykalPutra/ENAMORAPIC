<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Dashboard') — Enamorapic Admin 2</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
:root {
    --sidebar-bg: #0b1736;
    --sidebar-width: 300px;
    --accent: #d4a96a;
    --main-bg: #f0f3f9;
}
* { box-sizing: border-box; }
body { font-family: 'Inter', sans-serif; background: var(--main-bg); opacity: 0; transition: opacity .3s ease; overflow-x: hidden; }
body.page-ready { opacity: 1; }
body.page-leaving { opacity: 0; }

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
    position: fixed;
    top: 0; left: 0; bottom: 0;
    width: 300px;
    background: linear-gradient(180deg, #08112b 0%, #0d1c3f 40%, #091529 100%);
    z-index: 1000;
    display: flex;
    flex-direction: column;
    transition: transform .35s cubic-bezier(.4,0,.2,1);
    overflow: hidden;
    box-shadow: 6px 0 40px rgba(0,0,0,.35);
}

/* mesh background */
.sidebar::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 260px 200px at 110% -10%, rgba(212,169,106,.13) 0%, transparent 70%),
        radial-gradient(ellipse 180px 180px at -20% 80%, rgba(37,99,235,.09) 0%, transparent 70%),
        radial-gradient(ellipse 140px 140px at 80% 60%, rgba(16,185,129,.05) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}
/* noise grain overlay */
.sidebar::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
    background-size: 180px;
    pointer-events: none;
    z-index: 0;
    opacity: .6;
}

/* brand */
.sb-brand {
    padding: 26px 24px 22px;
    border-bottom: 1px solid rgba(255,255,255,.06);
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.sb-brand img {
    height: 38px;
    filter: brightness(0) invert(1);
    opacity: .93;
    transition: opacity .2s;
}
.sb-brand img:hover { opacity: 1; }
.sb-role-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 10px;
    background: rgba(212,169,106,.12);
    border: 1px solid rgba(212,169,106,.28);
    border-radius: 999px;
    padding: 4px 12px;
    font-size: .61rem;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: #D4A96A;
    font-weight: 700;
    backdrop-filter: blur(4px);
}
.sb-role-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #D4A96A;
    box-shadow: 0 0 6px #D4A96A, 0 0 12px rgba(212,169,106,.4);
    animation: dotPulse 2.4s ease infinite;
}
@keyframes dotPulse {
    0%,100% { box-shadow: 0 0 6px #D4A96A, 0 0 12px rgba(212,169,106,.4); }
    50%      { box-shadow: 0 0 10px #D4A96A, 0 0 22px rgba(212,169,106,.7); }
}

/* nav */
.sb-nav {
    flex: 1;
    overflow-y: auto;
    padding: 14px 14px;
    scrollbar-width: none;
    position: relative;
    z-index: 1;
}
.sb-nav::-webkit-scrollbar { display: none; }

.sb-section {
    font-size: .58rem;
    letter-spacing: .24em;
    text-transform: uppercase;
    color: rgba(255,255,255,.2);
    padding: 18px 12px 8px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}
.sb-section::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,.06);
    border-radius: 1px;
}

.sb-link {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 10px 14px;
    border-radius: 13px;
    color: rgba(255,255,255,.5);
    text-decoration: none;
    font-size: .875rem;
    font-weight: 500;
    transition: color .22s, background .22s, transform .18s;
    margin-bottom: 3px;
    position: relative;
    overflow: hidden;
    letter-spacing: .01em;
}
/* ripple layer */
.sb-link::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 13px;
    background: rgba(255,255,255,0);
    transition: background .22s;
}
/* glow accent line */
.sb-link::after {
    content: '';
    position: absolute;
    left: 0; top: 18%; bottom: 18%;
    width: 3px;
    border-radius: 0 3px 3px 0;
    background: transparent;
    transition: background .25s, box-shadow .25s, opacity .25s;
    opacity: 0;
}
.sb-link-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    background: rgba(255,255,255,.05);
    transition: background .22s, transform .22s, box-shadow .22s;
}
.sb-link span.sb-link-text {
    flex: 1;
    transition: transform .18s;
}
.sb-link:hover {
    color: #fff;
    background: rgba(255,255,255,.07);
    transform: translateX(4px);
}
.sb-link:hover .sb-link-icon {
    background: rgba(255,255,255,.12);
    transform: scale(1.08) rotate(-4deg);
}
.sb-link:hover::before {
    background: rgba(255,255,255,.02);
}
.sb-link.active {
    color: #fff;
    background: rgba(212,169,106,.13);
    transform: translateX(0);
}
.sb-link.active::after {
    background: linear-gradient(180deg, #D4A96A 0%, #f0c97a 50%, #D4A96A 100%);
    box-shadow: 0 0 10px rgba(212,169,106,.8), 0 0 20px rgba(212,169,106,.3);
    opacity: 1;
}
.sb-link.active .sb-link-icon {
    background: rgba(212,169,106,.2);
    box-shadow: 0 4px 14px rgba(212,169,106,.25);
    color: #D4A96A;
}
/* click ripple animation */
.sb-link.rippling::before {
    animation: sbRipple .45s ease forwards;
}
@keyframes sbRipple {
    0%   { background: rgba(255,255,255,.18); }
    100% { background: rgba(255,255,255,.0); }
}

.sb-badge {
    margin-left: auto;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
    font-size: .62rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 999px;
    line-height: 1.5;
    box-shadow: 0 2px 8px rgba(239,68,68,.4);
    animation: sbPulse 2.2s ease infinite;
}
@keyframes sbPulse {
    0%,100% { box-shadow: 0 2px 8px rgba(239,68,68,.4), 0 0 0 0 rgba(239,68,68,.3); }
    50%      { box-shadow: 0 2px 8px rgba(239,68,68,.4), 0 0 0 5px rgba(239,68,68,0); }
}

/* user card */
.sb-user {
    flex-shrink: 0;
    padding: 14px 18px;
    border-top: 1px solid rgba(255,255,255,.06);
    display: flex;
    align-items: center;
    gap: 13px;
    background: rgba(0,0,0,.2);
    backdrop-filter: blur(6px);
    position: relative;
    z-index: 1;
    transition: background .2s;
}
.sb-user:hover { background: rgba(0,0,0,.28); }
.sb-avatar {
    width: 42px; height: 42px;
    border-radius: 13px;
    background: linear-gradient(135deg, #D4A96A 0%, #f0c97a 100%);
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: .85rem;
    color: #0B1736;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(212,169,106,.45);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.sb-user:hover .sb-avatar {
    transform: scale(1.06);
    box-shadow: 0 6px 18px rgba(212,169,106,.55);
}
.avatar-media { width: 100%; height: 100%; object-fit: cover; }
.sb-user-info { flex: 1; min-width: 0; }
.sb-user-name {
    font-size: .85rem;
    font-weight: 700;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sb-user-role {
    font-size: .64rem;
    color: rgba(255,255,255,.35);
    text-transform: uppercase;
    letter-spacing: .12em;
    margin-top: 1px;
}
.sb-user-note {
    font-size: .62rem;
    color: rgba(255,255,255,.28);
    margin-top: 2px;
}
.sb-divider {
    height: 1px;
    background: rgba(255,255,255,.05);
    margin: 6px 10px;
    border-radius: 1px;
}

/* ─── MAIN ─────────────────────────────────── */
.main-content {
    margin-left: 300px;
    min-height: 100vh;
}

/* topbar */
.topbar {
    min-height: 66px;
    background: white;
    padding: 11px 24px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    gap: 16px;
    flex-wrap: wrap;
}
.topbar-left { display: flex; align-items: center; gap: 14px; }
.topbar-title { font-weight: 700; color: #1a2332; font-size: 1rem; line-height: 1.2; }
.topbar-subtitle { font-size: .67rem; letter-spacing: .1em; color: #6e7e9e; text-transform: uppercase; }
.topbar-center {
    flex: 1;
    min-width: 220px;
    max-width: 560px;
}
.top-search { position: relative; }
.top-search .bi-search {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #7f8fab;
    font-size: .92rem;
}
.top-search-input {
    width: 100%;
    border: 1px solid #d8e1ef;
    background: linear-gradient(180deg,#f9fbff 0%,#f3f7ff 100%);
    border-radius: 12px;
    height: 42px;
    padding: 0 14px 0 38px;
    font-size: .86rem;
    color: #1d2a44;
    transition: border-color .2s, box-shadow .2s;
}
.top-search-input:focus {
    outline: none;
    border-color: #2a4e9e;
    box-shadow: 0 0 0 4px rgba(42,78,158,.12);
}
.topbar-right { display: flex; align-items: center; gap: 10px; }
.hamburger {
    display: none;
    width: 40px; height: 40px;
    border-radius: 11px;
    border: 1.5px solid #e4eaf3;
    background: #fff;
    align-items: center; justify-content: center;
    cursor: pointer;
    color: #0b1736;
    font-size: 1.15rem;
    transition: all .2s;
}
.hamburger:hover { background: #0b1736; color: #fff; border-color: #0b1736; }
.top-action {
    width: 40px; height: 40px;
    border-radius: 12px;
    border: 1px solid #dce3f0;
    background: #fff;
    color: #3e4f72;
    display: flex;
    align-items: center; justify-content: center;
    position: relative;
    transition: all .2s;
}
.top-action:hover { background: #f7faff; color: #1f3568; }
.top-action-badge {
    position: absolute;
    right: -3px; top: -4px;
    min-width: 17px; height: 17px;
    border-radius: 999px;
    background: #ef4444;
    color: #fff;
    font-size: .63rem;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
    padding: 0 4px;
}
.notif-menu,
.account-menu {
    width: min(360px, calc(100vw - 24px));
    border-radius: 14px;
    border: 1px solid #e6ecf8;
    box-shadow: 0 20px 46px rgba(11,23,54,.14);
    padding: 0;
    overflow: hidden;
}
.notif-head {
    padding: 12px 14px;
    background: #f6f9ff;
    border-bottom: 1px solid #e7edf8;
}
.notif-item {
    padding: 11px 14px;
    border-bottom: 1px solid #eef2fa;
    text-decoration: none;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: #1d2a44;
    transition: background .18s;
}
.notif-item:hover { background: #f8fbff; }
.notif-list {
    max-height: 346px;
    overflow-y: auto;
    overscroll-behavior: contain;
    scroll-behavior: smooth;
}
.notif-section { border-bottom: 1px solid #edf2fb; }
.notif-section:last-child { border-bottom: none; }
.notif-section-title {
    position: sticky; top: 0; z-index: 1;
    background: #f9fbff;
    border-bottom: 1px solid #edf2fb;
    padding: 7px 14px;
    font-size: .68rem;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #6b7d9a;
    font-weight: 700;
}
.notif-list::-webkit-scrollbar { width: 8px; }
.notif-list::-webkit-scrollbar-thumb {
    background: #d4deef;
    border-radius: 999px;
    border: 2px solid #f7faff;
}
.notif-icon {
    width: 30px; height: 30px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .76rem;
    flex-shrink: 0;
}
.notif-icon.wedding { background: #eaf7ff; color: #1f62c2; }
.notif-icon.dp      { background: #fff4e8; color: #a35f06; }
.notif-icon.approved{ background: #e9f8ef; color: #1f8a4c; }
.notif-content { min-width: 0; }
.notif-title { font-size: .8rem; font-weight: 700; line-height: 1.2; }
.notif-sub   { font-size: .76rem; color: #546582; margin-top: 1px; }
.notif-time  { font-size: .7rem; color: #8da0bf; margin-top: 2px; }
.top-account-trigger {
    border: 1px solid #dce3f0;
    background: #fff;
    border-radius: 12px;
    padding: 3px 7px 3px 3px;
    display: flex; align-items: center; gap: 7px;
    color: #1d2a44;
}
.user-avatar {
    width: 38px; height: 38px;
    border-radius: 11px;
    background: #0b1736;
    display: flex; align-items: center; justify-content: center;
    color: white;
    font-weight: 700; font-size: .9rem;
    overflow: hidden;
}
.top-account-name {
    font-size: .8rem;
    font-weight: 700;
    max-width: 112px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.account-head {
    padding: 14px;
    background: linear-gradient(120deg,#1a2332 0%,#21314c 100%);
    color: #fff;
}
.account-logout {
    display: block;
    width: 100%;
    border: none;
    border-top: 1px solid #edf1f9;
    background: #fff8f8;
    color: #b42318;
    font-size: .82rem;
    font-weight: 700;
    padding: 10px 12px;
}

/* page content */
.page-content {
    padding: 25px;
    transform: translateY(12px);
    opacity: 0;
    transition: transform .45s cubic-bezier(.2,.8,.2,1), opacity .45s ease;
}
body.page-ready .page-content { transform: translateY(0); opacity: 1; }
body.page-leaving .page-content { transform: translateY(8px); opacity: 0; }

/* util */
.card { border: none; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,0.06); }
.card-header { background: white; border-bottom: 1px solid #f0f2f5; border-radius: 16px 16px 0 0 !important; padding: 18px 22px; font-weight: 700; color: #1a2332; }
.table th { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; font-weight: 600; border-bottom: 2px solid #f0f2f5; }
.table td { font-size: 0.875rem; vertical-align: middle; }
.badge { font-size: 0.72rem; letter-spacing: 0.5px; }
.alert { border-radius: 12px; border: none; font-size: 0.875rem; }
.table-custom thead th { background: #f8fafc; color: #6c757d; font-weight: 700; border-bottom: 2px solid #e9ecef; }
.table-custom tbody td { background: #ffffff; }
.table-custom tbody tr:hover td { background: #f9fbfd; }
.badge-role { padding: 5px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; background: rgba(25,118,210,.12); color: #1976d2; }
.btn-action { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: all .2s; text-decoration: none; }
.btn-action:hover { background: #eef3f9; transform: translateY(-2px); }

/* overlay transition */
.page-transition-overlay {
    position: fixed;
    inset: 0;
    pointer-events: none;
    opacity: 0;
    z-index: 3000;
    background: radial-gradient(circle at center, rgba(255,255,255,0) 0%, rgba(12,18,30,.18) 100%);
    transition: opacity .26s ease;
}
.page-transition-overlay.active { opacity: 1; }

/* ─── RESPONSIVE ──────────────────────────── */
@media(max-width:991px){
    .sidebar { transform: translateX(-100%); }
    .sidebar.show { transform: translateX(0); }
    .main-content { margin-left: 0; }
    .hamburger { display: flex; }
    .topbar { padding: 10px 14px; }
    .topbar-center { order: 3; max-width: 100%; flex-basis: 100%; }
    .topbar-right { margin-left: auto; }
}
@media(max-width:575px){
    .top-account-name { display: none; }
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
        <div class="sb-section">Main Menu</div>

        <a href="{{ route('admin2.dashboard') }}"
           class="sb-link {{ request()->routeIs('admin2.dashboard') ? 'active' : '' }}">
            <span class="sb-link-icon"><i class="bi bi-speedometer2"></i></span>
            <span class="sb-link-text">Dashboard</span>
        </a>

        <a href="{{ route('admin2.pesanan') }}"
           class="sb-link {{ request()->routeIs('admin2.pesanan*') ? 'active' : '' }}">
            <span class="sb-link-icon"><i class="bi bi-calendar-check"></i></span>
            <span class="sb-link-text">Data Pesanan</span>
        </a>

        <div class="sb-divider"></div>
        <div class="sb-section">Manajemen</div>

        <a href="{{ route('admin2.freelance.index') }}"
           class="sb-link {{ request()->routeIs('admin2.freelance*') ? 'active' : '' }}">
            <span class="sb-link-icon"><i class="bi bi-person-badge"></i></span>
            <span class="sb-link-text">Freelance</span>
        </a>

        <a href="{{ route('admin2.pegawai.index') }}"
           class="sb-link {{ request()->routeIs('admin2.pegawai*') ? 'active' : '' }}">
            <span class="sb-link-icon"><i class="bi bi-people"></i></span>
            <span class="sb-link-text">Pegawai</span>
        </a>

        <a href="{{ route('admin2.customer.index') }}"
           class="sb-link {{ request()->routeIs('admin2.customer*') ? 'active' : '' }}">
            <span class="sb-link-icon"><i class="bi bi-person-lines-fill"></i></span>
            <span class="sb-link-text">Customer</span>
        </a>

        <a href="{{ route('admin2.wo.index') }}"
           class="sb-link {{ request()->routeIs('admin2.wo*') ? 'active' : '' }}">
            <span class="sb-link-icon"><i class="bi bi-briefcase"></i></span>
            <span class="sb-link-text">Wedding Organizer</span>
        </a>

        <a href="{{ route('admin2.leads.index') }}"
           class="sb-link {{ request()->routeIs('admin2.leads*') ? 'active' : '' }}">
            <span class="sb-link-icon"><i class="bi bi-kanban"></i></span>
            <span class="sb-link-text">Leads</span>
        </a>

        <a href="{{ route('admin2.laporan') }}"
           class="sb-link {{ request()->routeIs('admin2.laporan*') ? 'active' : '' }}">
            <span class="sb-link-icon"><i class="bi bi-bar-chart-line"></i></span>
            <span class="sb-link-text">Laporan</span>
        </a>

        <div class="sb-divider"></div>
        <div class="sb-section">Lainnya</div>

        <a href="{{ route('home') }}" target="_blank" class="sb-link">
            <span class="sb-link-icon"><i class="bi bi-globe"></i></span>
            <span class="sb-link-text">Lihat Website</span>
            <i class="bi bi-arrow-up-right" style="font-size:.7rem;color:rgba(255,255,255,.25);margin-left:auto;"></i>
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

<div class="sb-overlay" id="sbOverlay" onclick="closeSidebar()" style="display:none;position:fixed;inset:0;background:rgba(11,23,54,.45);z-index:999;backdrop-filter:blur(3px);opacity:0;transition:opacity .3s;"></div>

<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            @if(!trim($__env->yieldContent('hideTopbarTitle')))
            <div>
                <div class="topbar-subtitle">Enamorapic Studio</div>
                <div class="topbar-title">@yield('title', 'Dashboard')</div>
            </div>
            @endif
        </div>

        <div class="topbar-center">
            <form method="GET" action="{{ route('admin2.search') }}" class="top-search">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    name="q"
                    class="top-search-input"
                    placeholder="Cari booking, customer, pegawai, atau freelance..."
                    value="{{ request('q') }}"
                >
            </form>
        </div>

        <div class="topbar-right">
            <div class="dropdown">
                @php
                    $notifCount = (int) (($admin2NotifItems ?? collect())->count());
                @endphp
                <button class="top-action" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Notifikasi">
                    <i class="bi bi-bell-fill"></i>
                    @if($notifCount > 0)
                        <span class="top-action-badge">{{ $notifCount > 99 ? '99+' : $notifCount }}</span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end notif-menu">
                    <div class="notif-head">
                        <div style="font-weight:700;color:#0b1736;">Ringkasan Peringatan Booking</div>
                        <div style="font-size:.75rem;color:#657b9c;">
                            Wedding terdekat: <strong>{{ $admin2UpcomingWeddingCount ?? 0 }}</strong> •
                            Masih DP: <strong>{{ $admin2DpCount ?? 0 }}</strong> •
                            Baru approve: <strong>{{ $admin2ApprovedCount ?? 0 }}</strong>
                        </div>
                    </div>

                    <div class="notif-list">
                        @php
                            $notifItems = $admin2NotifItems ?? collect();
                            $notifGroups = [
                                'wedding'  => 'Wedding Terdekat',
                                'dp'       => 'Masih DP',
                                'approved' => 'Baru Di-approve',
                            ];
                        @endphp

                        @if($notifItems->isEmpty())
                            <div class="notif-item" style="cursor:default;">
                                <div class="notif-content">
                                    <div class="notif-sub">Belum ada notifikasi peringatan saat ini.</div>
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
                                                <div class="notif-icon {{ $item['type'] ?? 'wedding' }}">
                                                    @if(($item['type'] ?? '') === 'dp')
                                                        <i class="bi bi-wallet2"></i>
                                                    @elseif(($item['type'] ?? '') === 'approved')
                                                        <i class="bi bi-patch-check"></i>
                                                    @else
                                                        <i class="bi bi-calendar-heart"></i>
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
                <button class="top-account-trigger" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Akun">
                    <div class="user-avatar">
                        @if(Auth::user()?->profile_photo_url)
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="avatar-media">
                        @else
                            {{ Auth::user()->avatar_initial ?? 'A' }}
                        @endif
                    </div>
                    <span class="top-account-name">{{ Auth::user()->nama_lengkap ?? 'Admin' }}</span>
                    <i class="bi bi-chevron-down" style="font-size:.75rem;color:#6f809f;"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end account-menu">
                    <div class="account-head">
                        <div style="font-size:.86rem;font-weight:700;line-height:1.2;">{{ Auth::user()->nama_lengkap ?? 'Admin' }}</div>
                        <div style="font-size:.72rem;color:rgba(255,255,255,.72);">Login sebagai {{ Auth::user()->role ?? 'ADMIN' }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="account-logout">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
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
    </div>
</div>

<div class="page-transition-overlay" id="pageTransitionOverlay"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('sbOverlay');
    sb.classList.toggle('show');
    if (sb.classList.contains('show')) {
        ov.style.display = 'block';
        setTimeout(() => ov.style.opacity = '1', 10);
    } else {
        ov.style.opacity = '0';
        setTimeout(() => ov.style.display = 'none', 300);
    }
}
function closeSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('sbOverlay');
    sb.classList.remove('show');
    ov.style.opacity = '0';
    setTimeout(() => ov.style.display = 'none', 300);
}

/* Sidebar ripple on click */
document.querySelectorAll('.sb-link').forEach(link => {
    link.addEventListener('click', function() {
        this.classList.remove('rippling');
        void this.offsetWidth;
        this.classList.add('rippling');
        setTimeout(() => this.classList.remove('rippling'), 460);
    });
});

(() => {
    const overlay = document.getElementById('pageTransitionOverlay');
    const duration = 240;

    const showReady = () => {
        document.body.classList.remove('page-leaving');
        document.body.classList.add('page-ready');
        if (overlay) overlay.classList.remove('active');
    };

    window.addEventListener('load', showReady);
    window.addEventListener('pageshow', showReady);

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]');
        if (!link) return;
        const href = link.getAttribute('href') || '';
        if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
        if (link.target === '_blank' || link.hasAttribute('download')) return;
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        let nextUrl;
        try { nextUrl = new URL(link.href, window.location.href); } catch { return; }
        if (nextUrl.origin !== window.location.origin) return;
        if (nextUrl.href === window.location.href) return;
        event.preventDefault();
        document.body.classList.add('page-leaving');
        if (overlay) overlay.classList.add('active');
        window.setTimeout(() => { window.location.href = nextUrl.href; }, duration);
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