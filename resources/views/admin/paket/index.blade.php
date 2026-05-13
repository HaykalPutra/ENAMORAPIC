@extends('layouts.admin')
@section('title','Paket Foto')
@section('hideTopbarTitle', '1')
@section('styles')
<style>
/* card layout for paket */
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to { opacity:1; transform:translateY(0); }
}

.package-card {
    border-radius: 16px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    margin-bottom: 28px;
    overflow: hidden;
    animation: fadeInUp 0.5s ease forwards;
    background:#fff;
}
.package-card:hover {
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    transform: translateY(-4px);
    border-color: #d4af37;
}
.package-card img {
    width:100%;
    height:160px;
    object-fit:cover;
}
.package-body {
    padding: 24px;
}
.package-title {
    font-size:1.25rem;
    font-weight:700;
    color:#1a2332;
    margin-bottom:8px;
}
.package-badge {
    font-size:0.75rem;
    font-weight:600;
    padding:4px 10px;
    border-radius:12px;
}
.package-price {
    font-size:1.1rem;
    font-weight:700;
    color:#1976d2;
    margin-top:6px;
}
.package-desc {
    font-size:0.9rem;
    color:#666;
    line-height: 1.55;
    margin:14px 0 18px;
    min-height:56px;
    overflow:hidden;
}
.action-btn { width:34px;height:34px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;transition:all 0.2s; }
.action-btn:hover { transform:scale(1.1); }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1a2332;">
            <i class="bi bi-box-seam" style="color:#d4af37; margin-right:10px;"></i>Paket Foto
        </h4>
        <p class="text-muted mb-0">Kelola katalog paket foto dengan tampilan yang konsisten</p>
    </div>
    <a href="{{ route('admin.paket.create') }}" class="btn btn-primary px-4">
        <i class="bi bi-plus-lg me-2"></i>Tambah Paket Foto
    </a>
</div>

@if(empty($pakets) || (is_countable($pakets) && count($pakets) === 0))
    <div class="text-center py-5 empty-state">
        <i class="bi bi-box-seam fs-1 d-block mb-2 opacity-25"></i>
        <p class="text-muted mt-3">Belum ada data paket.</p>
    </div>
@else
    <div class="row g-4">
        @foreach($pakets as $p)
        @php
            $badgeClass = match($p->kategori) {
                'Wedding'     => 'bg-primary',
                'Pre-Wedding' => 'bg-info',
                'Engagement'  => 'bg-success',
                default       => 'bg-secondary'
            };

            $fallback = 'https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800';
            $imgPath  = public_path('assets/images/' . ($p->gambar ?? ''));
            $imgUrl = ($p->gambar && file_exists($imgPath))
                      ? asset('assets/images/' . $p->gambar)
                      : $fallback;
        @endphp
        <div class="col-md-6 col-xl-4">
            <div class="package-card h-100">
                <img src="{{ $imgUrl }}" alt="{{ $p->nama_paket }}" onerror="this.src='https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800'">
                <div class="package-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="package-title">{{ $p->nama_paket }}</div>
                        <span class="package-badge {{ $badgeClass }}">{{ $p->kategori }}</span>
                    </div>
                    <div class="package-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                    <div class="package-desc">{{ Str::limit(strip_tags($p->deskripsi), 60) }}</div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.paket.edit', $p->paket_id) }}" class="action-btn btn btn-warning btn-sm" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.paket.destroy', $p->paket_id) }}" method="POST" style="display:inline;"
                            data-confirm="Hapus paket {{ addslashes($p->nama_paket) }}?"
                            data-confirm-type="delete"
                            data-confirm-title="Hapus Paket">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn btn btn-danger btn-sm" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
