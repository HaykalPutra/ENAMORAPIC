@extends('layouts.admin')
@section('title','Pesanan Website')
@section('styles')
<style>
.page-enter{opacity:0;animation:fadeIn 0.8s ease forwards;}
@keyframes fadeIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.custom-card{border-radius:12px;border:none;box-shadow:0 2px 12px rgba(0,0,0,0.06);background:#fff;overflow:hidden;}
.table thead th{background-color:#f8f9fa;border-bottom:2px solid #e9ecef;font-weight:600;color:#495057;padding:16px;}
.table tbody td{padding:14px 16px;vertical-align:middle;}
.pesanan-card{border-radius:12px;border:none;box-shadow:0 2px 12px rgba(0,0,0,0.06);background:#fff;transition:all 0.2s ease;margin-bottom:12px;}
.pesanan-card:hover{box-shadow:0 8px 24px rgba(0,0,0,0.1);transform:translateY(-2px);}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}
.pulse{animation:pulse 2s infinite;}
</style>
@endsection

@section('content')
<div class="container-fluid page-enter py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fas fa-bell text-warning me-2"></i>Pesanan dari Website
            </h3>
            <p class="text-muted mb-0">Notifikasi booking masuk dari form website</p>
        </div>
        @if($jumlahPending > 0)
        <span class="badge bg-danger px-3 py-2 fs-6">
            <i class="fas fa-circle pulse me-1"></i>{{ $jumlahPending }} Pesanan Baru
        </span>
        @endif
    </div>

    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Pemesan</th>
                            <th>No. WhatsApp</th>
                            <th>Paket</th>
                            <th>Tgl Booking</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanans as $no => $p)
                        @php
                            $wa = preg_replace('/[^0-9]/','', $p->no_wa ?? '');
                            if(substr($wa,0,1)=='0') $wa='62'.substr($wa,1);
                            $msg = "Halo Kak {$p->nama_pemesan}, kami dari Enamorapic ingin mengkonfirmasi pesanan Anda untuk paket *{$p->nama_paket}*. Mohon konfirmasi lebih lanjut ya 😊";
                            $linkWA = "https://wa.me/{$wa}?text=".urlencode($msg);
                        @endphp
                        <tr class="{{ $p->status=='pending' ? 'table-warning' : '' }}">
                            <td class="ps-4 fw-bold text-muted">{{ $no+1 }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $p->nama_pemesan }}</span>
                                @if($p->status=='pending')
                                <span class="badge bg-danger ms-1 pulse">Baru</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ $linkWA }}" target="_blank" class="text-success text-decoration-none fw-bold">
                                    <i class="fab fa-whatsapp me-1"></i>{{ $p->no_wa }}
                                </a>
                            </td>
                            <td><span class="fw-bold">{{ $p->nama_paket ?? 'N/A' }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_booking)->format('d M Y') }}</td>
                            <td class="text-muted small">{{ Str::limit($p->lokasi, 30) }}</td>
                            <td>
                                @if($p->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($p->status == 'pending')
                                <form action="{{ route('admin.pesanan-website.done', $p->pesanan_id) }}" method="POST"
                                      style="display:inline;">
                                    @csrf @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm"
                                            data-confirm="Tandai pesanan ini sebagai selesai?"
                                            data-confirm-title="Konfirmasi">
                                        <i class="fas fa-check me-1"></i>Tandai Selesai
                                    </button>
                                </form>
                                @else
                                <span class="text-muted small"><i class="fas fa-check-circle text-success me-1"></i>Done</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                                Belum ada pesanan masuk dari website.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $pesanans->links() }}
    </div>

</div>
@endsection
