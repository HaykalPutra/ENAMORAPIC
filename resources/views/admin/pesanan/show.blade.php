@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . $booking->booking_id)
@section('hideTopbarTitle', '1')

@section('content')
@php
    $isViewOnly = request('mode') === 'view';
    $statusClass = match (strtolower((string) $booking->job_status)) {
        'selesai' => 'bg-success',
        'booked', 'confirmed' => 'bg-primary',
        'batal' => 'bg-danger',
        default => 'bg-warning text-dark',
    };
@endphp

<style>
    .booking-hero-card {
        border: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafd 100%);
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.08);
    }

    .mode-badge {
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
        padding: 6px 12px;
        letter-spacing: .3px;
    }
</style>

<div class="mb-3">
    <a href="{{ route('admin.pesanan') }}" class="btn btn-sm btn-outline-secondary rounded-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card booking-hero-card mb-4">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3 p-3 p-md-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark">Booking #{{ $booking->booking_id }} - {{ $booking->nama_client }}</h5>
            <small class="text-muted">{{ $booking->tgl_acara ? \Carbon\Carbon::parse($booking->tgl_acara)->format('d F Y') : '-' }} • {{ $booking->display_paket_name }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge {{ $statusClass }} mode-badge">{{ $booking->job_status }}</span>
            <span class="badge bg-dark-subtle text-dark mode-badge">{{ $booking->status_pembayaran }}</span>
            <span class="badge {{ $isViewOnly ? 'bg-secondary' : 'bg-info text-dark' }} mode-badge">
                {{ $isViewOnly ? 'Mode Lihat' : 'Mode Edit' }}
            </span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Detail Booking #{{ $booking->booking_id }}
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
                            @if($booking->details->count() > 0)
                                @foreach($booking->details as $d)
                                <span class="badge bg-dark px-3 py-2">{{ $d->paket->nama_paket ?? '-' }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-dark px-3 py-2">{{ $booking->display_paket_name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4" id="update-status">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2"></i>{{ $isViewOnly ? 'Informasi Status' : 'Update Status' }}</div>
            <div class="card-body p-4">
                @if($isViewOnly)
                    <div class="alert alert-light border rounded-3 mb-3">
                        <div class="small text-uppercase text-muted fw-semibold mb-1">Status Acara</div>
                        <div class="fw-bold">{{ $booking->job_status }}</div>
                    </div>
                    <div class="alert alert-light border rounded-3 mb-3">
                        <div class="small text-uppercase text-muted fw-semibold mb-1">Status Pembayaran</div>
                        <div class="fw-bold">{{ $booking->status_pembayaran }}</div>
                    </div>
                    <a href="{{ route('admin.pesanan.show', ['id' => $booking->booking_id, 'mode' => 'edit']) }}#update-status" class="btn btn-info w-100 rounded-3 text-white">
                        <i class="bi bi-pencil-square me-2"></i>Masuk Mode Edit
                    </a>
                @else
                    <form action="{{ route('admin.pesanan.status', $booking->booking_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Status Acara</label>
                            <select name="job_status" class="form-select rounded-3">
                                @foreach(['Pending','Booked','Selesai','Batal'] as $s)
                                <option value="{{ $s }}" {{ $booking->job_status==$s?'selected':'' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Status Pembayaran</label>
                            <select name="status_pembayaran" class="form-select rounded-3">
                                <option value="DP" {{ $booking->status_pembayaran=='DP'?'selected':'' }}>DP</option>
                                <option value="Lunas" {{ $booking->status_pembayaran=='Lunas'?'selected':'' }}>Lunas</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 rounded-3">Simpan Perubahan</button>
                    </form>

                    <hr>

                      <form action="{{ route('admin.pesanan.destroy', $booking->booking_id) }}" method="POST"
                          data-confirm="Yakin hapus booking ini?"
                          data-confirm-type="delete"
                          data-confirm-title="Hapus Booking">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-3">
                            <i class="bi bi-trash me-2"></i>Hapus Booking
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
