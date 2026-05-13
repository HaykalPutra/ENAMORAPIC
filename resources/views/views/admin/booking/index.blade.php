@extends('layouts.admin')
@section('title','Manajemen Booking')
@section('styles')
<style>
.page-enter{opacity:0;animation:fadeIn 0.8s ease forwards;}
@keyframes fadeIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.booking-card{border-radius:12px;border:none;box-shadow:0 2px 12px rgba(0,0,0,0.06);transition:all 0.3s ease;margin-bottom:16px;background:#fff;}
.booking-card:hover{box-shadow:0 8px 24px rgba(0,0,0,0.12);transform:translateY(-2px);}
.status-badge{padding:8px 16px;border-radius:20px;font-weight:600;font-size:0.85rem;}
.action-btn{width:36px;height:36px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;transition:all 0.2s ease;}
.action-btn:hover{transform:scale(1.1);}
.filter-card{border-radius:12px;border:none;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;box-shadow:0 4px 20px rgba(102,126,234,0.3);}
.modal-content{border-radius:16px;border:none;}
.detail-row{padding:12px 0;border-bottom:1px solid #f0f0f0;}
.detail-row:last-child{border-bottom:none;}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}
.animate-pulse{animation:pulse 2s cubic-bezier(0.4,0,0.6,1) infinite;}
</style>
@endsection

@section('content')
<div class="container-fluid page-enter py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-calendar-alt text-primary me-2"></i>Manajemen Booking</h3>
            <p class="text-muted mb-0">Kelola semua pesanan dari customer</p>
        </div>
        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
            <i class="fas fa-circle me-1 animate-pulse"></i>Live Updates
        </span>
    </div>

    {{-- FILTER --}}
    <div class="card filter-card mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-white">Status Acara</label>
                    <select name="status" class="form-select bg-white text-dark">
                        <option value="">Semua Status</option>
                        @foreach(['Pending','Confirmed','Booked','Selesai','Batal'] as $s)
                        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-white">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control bg-white" value="{{ request('dari') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-white">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control bg-white" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-light w-100 fw-bold text-primary">
                        <i class="fas fa-filter me-2"></i>Filter Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- BOOKING CARDS --}}
    <div class="row">
        @forelse($bookings as $d)
        @php
            $badgeMap = ['Pending'=>'warning','Confirmed'=>'primary','Booked'=>'info','Selesai'=>'success','Batal'=>'danger'];
            $bc = $badgeMap[$d->status_acara] ?? 'secondary';
            $wa = preg_replace('/[^0-9]/','', $d->no_wa ?? '');
            if(substr($wa,0,1)=='0') $wa='62'.substr($wa,1);
            $pesan = "Halo Kak {$d->nama_client}, terima kasih sudah mempercayakan Enamora untuk acara Anda!";
            $linkWA = "https://wa.me/{$wa}?text=".urlencode($pesan);
        @endphp

        <div class="col-lg-6">
            <div class="booking-card card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">{{ $d->nama_client }}</h5>
                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($d->tgl_acara)->format('d M Y') }}
                            </small>
                        </div>
                        <span class="status-badge bg-{{ $bc }} bg-opacity-10 text-{{ $bc }}">{{ $d->status_acara }}</span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block" style="font-size:0.75rem">PAKET</small>
                            <strong class="text-dark">{{ $d->nama_paket ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block" style="font-size:0.75rem">TOTAL</small>
                            <strong class="text-primary">Rp {{ number_format($d->total_transaksi,0,',','.') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block" style="font-size:0.75rem">PEMBAYARAN</small>
                            <span class="badge bg-{{ $d->status_pembayaran=='Lunas'?'success':'warning' }}">
                                {{ $d->status_pembayaran }}
                            </span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block" style="font-size:0.75rem">WHATSAPP</small>
                            <a href="{{ $linkWA }}" target="_blank" class="text-success text-decoration-none fw-bold">
                                <i class="fab fa-whatsapp me-1"></i>Chat Client
                            </a>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end pt-2 border-top">
                        <button class="btn btn-primary btn-sm action-btn"
                                data-bs-toggle="modal" data-bs-target="#detailModal{{ $d->booking_id }}" title="Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-info btn-sm action-btn text-white"
                                data-bs-toggle="modal" data-bs-target="#editModal{{ $d->booking_id }}" title="Update Status">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL --}}
        <div class="modal fade" id="detailModal{{ $d->booking_id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Detail Booking #{{ $d->booking_id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="detail-row d-flex justify-content-between">
                            <span class="text-muted">Nama Client</span>
                            <strong>{{ $d->nama_client }}</strong>
                        </div>
                        <div class="detail-row d-flex justify-content-between">
                            <span class="text-muted">No. WhatsApp</span>
                            <strong>{{ $d->no_wa }}</strong>
                        </div>
                        <div class="detail-row d-flex justify-content-between">
                            <span class="text-muted">Paket</span>
                            <strong>{{ $d->nama_paket ?? 'N/A' }}</strong>
                        </div>
                        <div class="detail-row d-flex justify-content-between">
                            <span class="text-muted">Tgl Booking</span>
                            <strong>{{ \Carbon\Carbon::parse($d->tgl_booking)->format('d M Y') }}</strong>
                        </div>
                        <div class="detail-row d-flex justify-content-between">
                            <span class="text-muted">Tgl Acara</span>
                            <strong>{{ \Carbon\Carbon::parse($d->tgl_acara)->format('d M Y') }}</strong>
                        </div>
                        <div class="detail-row d-flex justify-content-between">
                            <span class="text-muted">Status Acara</span>
                            <span class="badge bg-{{ $bc }}">{{ $d->status_acara }}</span>
                        </div>
                        <div class="detail-row d-flex justify-content-between">
                            <span class="text-muted">Total</span>
                            <h5 class="text-primary mb-0 fw-bold">Rp {{ number_format($d->total_transaksi,0,',','.') }}</h5>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div class="modal fade" id="editModal{{ $d->booking_id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Update Status Booking #{{ $d->booking_id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.booking.update', $d->booking_id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status Pembayaran</label>
                                <select name="status_pembayaran" class="form-select">
                                    @foreach(['DP','Lunas'] as $sp)
                                    <option value="{{ $sp }}" {{ $d->status_pembayaran==$sp?'selected':'' }}>{{ $sp }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status Acara</label>
                                <select name="status_acara" class="form-select">
                                    @foreach(['Pending','Confirmed','Booked','Selesai','Batal'] as $sa)
                                    <option value="{{ $sa }}" {{ $d->status_acara==$sa?'selected':'' }}>{{ $sa }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @empty
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                <h5>Tidak ada data booking</h5>
                <p class="mb-0 text-muted">Silakan ubah filter atau tunggu pesanan baru masuk</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->appends(request()->query())->links() }}
    </div>

</div>
@endsection

@section('scripts')
<script>
// Pindahkan semua modal ke body agar backdrop tidak menutupi
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.modal').forEach(m => document.body.appendChild(m));
});
</script>
@endsection
