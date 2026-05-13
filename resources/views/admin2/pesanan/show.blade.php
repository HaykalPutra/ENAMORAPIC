@extends('layouts.admin2')
@section('title', 'Detail Pesanan #' . $booking->booking_id)
@section('hideTopbarTitle', '1')

@section('content')
@php
    $statusClass = match (strtolower((string) $booking->job_status)) {
        'selesai' => 'bg-success',
        'booked', 'confirmed' => 'bg-primary',
        'batal' => 'bg-danger',
        default => 'bg-warning text-dark',
    };
    $activeRole = strtoupper((string) (auth()->user()->role ?? 'ADMIN'));
    $isCeo = $activeRole === 'CEO';
@endphp

<div class="mb-3">
    <a href="{{ route('admin2.pesanan') }}" class="btn btn-sm btn-outline-secondary rounded-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

@if($isCeo)
<div class="alert alert-primary border-0 mb-4" style="border-radius:12px;">
    <i class="bi bi-stars me-2"></i>
    Mode CEO di panel Admin2 ini hanya read-only. Untuk validasi status, buka
    <a href="{{ route('admin.pesanan.show', ['id' => $booking->booking_id, 'mode' => 'edit']) }}#update-status" class="alert-link fw-bold">halaman validasi CEO</a>.
</div>
@else
<div class="alert alert-warning border-0 mb-4" style="border-radius:12px;">
    <i class="bi bi-person-lock me-2"></i>
    Mode ADMIN: hanya bisa melihat detail booking, tanpa akses ubah status.
</div>
@endif

<div class="card border-0 shadow-sm mb-4" style="border-radius:14px; background:linear-gradient(135deg,#ffffff 0%,#f7f9fc 100%);">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3 p-3 p-md-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark">Booking #{{ $booking->booking_id }} - {{ $booking->nama_client }}</h5>
            <small class="text-muted">{{ $booking->tgl_acara ? \Carbon\Carbon::parse($booking->tgl_acara)->format('d F Y') : '-' }} • {{ $booking->display_paket_name }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge {{ $statusClass }} px-3 py-2">{{ $booking->job_status }}</span>
            <span class="badge bg-dark-subtle text-dark px-3 py-2">{{ $booking->status_pembayaran }}</span>
            <span class="badge {{ $isCeo ? 'bg-primary' : 'bg-secondary' }} px-3 py-2">{{ $activeRole }} Read Only</span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-2"></i>Detail Booking</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-semibold">Nama Client</label>
                        <div class="fw-semibold mt-1">{{ $booking->nama_client }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-semibold">No WhatsApp</label>
                        <div class="mt-1">
                            @if($booking->customer && $booking->customer->no_wa !== '-')
                            <a href="https://wa.me/{{ $booking->customer->no_wa }}" target="_blank" class="text-success text-decoration-none">
                                <i class="bi bi-whatsapp me-1"></i>{{ $booking->customer->no_wa }}
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-semibold">Tanggal Booking</label>
                        <div class="mt-1">{{ $booking->tgl_booking ? \Carbon\Carbon::parse($booking->tgl_booking)->format('d F Y') : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-semibold">Tanggal Acara</label>
                        <div class="mt-1 fw-semibold text-primary">{{ $booking->tgl_acara ? \Carbon\Carbon::parse($booking->tgl_acara)->format('d F Y') : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-semibold">Total Transaksi</label>
                        <div class="mt-1 fw-bold fs-5">Rp {{ number_format((float) $booking->total_transaksi,0,',','.') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-semibold">Paket</label>
                        <div class="mt-1">
                            <span class="badge bg-dark px-3 py-2">{{ $booking->display_paket_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2"></i>Akses Validasi</h6>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-light border rounded-3 mb-3">
                    <div class="small text-uppercase text-muted fw-semibold mb-1">Status Acara</div>
                    <div class="fw-bold">{{ $booking->job_status }}</div>
                </div>
                <div class="alert alert-light border rounded-3 mb-3">
                    <div class="small text-uppercase text-muted fw-semibold mb-1">Status Pembayaran</div>
                    <div class="fw-bold">{{ $booking->status_pembayaran }}</div>
                </div>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Perubahan status hanya bisa dilakukan oleh CEO di menu admin utama.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
