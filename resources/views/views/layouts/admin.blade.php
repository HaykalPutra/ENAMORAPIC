<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Enamora - @yield('title')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<style>
.navbar-admin {
    background: linear-gradient(135deg, #1a2332 0%, #2d3e50 100%);
    box-shadow: 0 2px 20px rgba(0,0,0,0.3);
    padding: 12px 0;
}
.navbar-admin .navbar-brand img { height: 40px; }
.navbar-admin .nav-link {
    color: rgba(255,255,255,0.8) !important;
    font-weight: 500;
    padding: 8px 16px !important;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.navbar-admin .nav-link:hover,
.navbar-admin .nav-link.active {
    color: #fff !important;
    background: rgba(255,255,255,0.1);
}
.navbar-admin .dropdown-menu {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-radius: 12px;
}
.navbar-admin .avatar i { font-size: 1.8rem; color: #d4af37; }
body { background: #f4f6f9; }
.main-content { padding-top: 80px; padding-bottom: 40px; }
.alert-flash {
    position: fixed; top: 80px; right: 20px; z-index: 9999;
    min-width: 300px; border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    animation: slideInRight 0.4s ease;
}
@keyframes slideInRight {
    from { transform: translateX(100px); opacity: 0; }
    to   { transform: translateX(0);     opacity: 1; }
}
</style>
@yield('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top navbar-admin">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('assets/images/enamora.png') }}" alt="Enamora">
    </a>
    <button class="navbar-toggler border-0" style="filter:invert(1);" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
             href="{{ route('admin.dashboard') }}">
            <i class="fas fa-chart-line me-1"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.pesanan-website*') ? 'active' : '' }}"
             href="{{ route('admin.pesanan-website.index') }}">
            <i class="fas fa-bell me-1"></i> Pesanan Web
            @if(isset($jumlahPesananBaru) && $jumlahPesananBaru > 0)
              <span class="badge bg-danger rounded-pill ms-1">{{ $jumlahPesananBaru }}</span>
            @endif
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.booking*') ? 'active' : '' }}"
             href="{{ route('admin.booking.index') }}">
            <i class="fas fa-calendar-check me-1"></i> Booking
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.paket*') ? 'active' : '' }}"
             href="{{ route('admin.paket.index') }}">
            <i class="fas fa-box me-1"></i> Paket
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}"
             href="{{ route('admin.laporan.index') }}">
            <i class="fas fa-file-invoice-dollar me-1"></i> Laporan
          </a>
        </li>
        <li class="nav-item ms-3 dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
             href="#" data-bs-toggle="dropdown">
            <div class="avatar"><i class="fas fa-user-circle"></i></div>
            <span class="fw-bold text-white">{{ Auth::user()->nama_lengkap }}</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow border-0">
            <li class="dropdown-header">Role: {{ Auth::user()->role }}</li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-danger fw-bold">
                  <i class="fas fa-sign-out-alt me-2"></i> Keluar
                </button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

@if(session('success'))
<div class="alert alert-success alert-flash alert-dismissible">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-flash alert-dismissible">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="main-content">
  <div class="container-fluid px-4">
    @yield('content')
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
setTimeout(function(){
    document.querySelectorAll('.alert-flash').forEach(el => {
        el.style.transition='opacity 0.5s'; el.style.opacity='0';
        setTimeout(()=>el.remove(),500);
    });
}, 4000);
</script>
@yield('scripts')
</body>
</html>
