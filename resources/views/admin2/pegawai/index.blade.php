@extends('layouts.admin2')
@section('title','Data Pegawai')
@section('hideTopbarTitle', '1')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<style>
:root {
    --navy: #0d1b2e;
    --navy-mid: #152440;
    --navy-light: #1e3358;
    --gold: #c9a84c;
    --gold-light: #e2c06e;
    --gold-muted: rgba(201,168,76,.15);
    --surface: #f8fafd;
    --border: rgba(30,51,88,.1);
    --text-main: #0d1b2e;
    --text-muted: #6b7a96;
    --radius: 14px;
    --shadow: 0 4px 20px rgba(13,27,46,.07);
}

body, .pegawai-wrap * { font-family: 'DM Sans', sans-serif; }

.pegawai-hero {
    background: var(--navy);
    border-radius: 20px;
    padding: 28px 32px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.pegawai-hero::before {
    content: '';
    position: absolute;
    right: -40px; top: -60px;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(201,168,76,.18) 0%, transparent 70%);
    pointer-events: none;
}
.pegawai-hero::after {
    content: '';
    position: absolute;
    left: 200px; bottom: -80px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,.04) 0%, transparent 70%);
    pointer-events: none;
}
.hero-icon {
    width: 48px; height: 48px;
    background: var(--gold-muted);
    border: 1px solid rgba(201,168,76,.3);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    color: var(--gold);
    font-size: 1.25rem;
    margin-bottom: 12px;
}
.hero-title {
    font-family: 'Syne', sans-serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
    letter-spacing: -.3px;
}
.hero-sub { color: rgba(255,255,255,.55); font-size: .875rem; margin: 0; }

.btn-add-pegawai {
    background: var(--gold);
    color: var(--navy) !important;
    font-weight: 600;
    font-size: .875rem;
    padding: 10px 22px;
    border-radius: 10px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: background .2s, transform .15s;
    white-space: nowrap;
    position: relative; z-index: 1;
}
.btn-add-pegawai:hover {
    background: var(--gold-light);
    transform: translateY(-1px);
    color: var(--navy) !important;
}

.pegawai-table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.pegawai-table-wrap table { width: 100%; border-collapse: collapse; }

.pegawai-table-wrap thead tr {
    background: var(--navy);
}
.pegawai-table-wrap thead th {
    padding: 14px 18px;
    font-size: .7rem;
    font-weight: 600;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: rgba(255,255,255,.5);
    border: none;
    white-space: nowrap;
}
.pegawai-table-wrap thead th:first-child { border-radius: 0; }

.pegawai-table-wrap tbody tr {
    border-bottom: 1px solid #f0f4fa;
    transition: background .15s;
}
.pegawai-table-wrap tbody tr:last-child { border-bottom: none; }
.pegawai-table-wrap tbody tr:hover { background: #f7faff; }

.pegawai-table-wrap tbody td {
    padding: 14px 18px;
    vertical-align: middle;
    color: var(--text-main);
    font-size: .875rem;
}

.name-cell {
    font-weight: 600;
    color: var(--navy);
    font-size: .9rem;
}

.badge-role {
    background: rgba(13,27,46,.07);
    color: var(--navy-light);
    border: 1px solid rgba(13,27,46,.1);
    border-radius: 999px;
    font-size: .72rem;
    font-weight: 600;
    padding: 4px 12px;
    letter-spacing: .3px;
    display: inline-block;
}

.gear-text { color: var(--text-muted); font-size: .82rem; }

.rate-text {
    font-weight: 700;
    color: var(--navy);
    font-size: .9rem;
}
.rate-text span {
    font-size: .72rem;
    font-weight: 500;
    color: var(--text-muted);
    margin-right: 2px;
}

.location-text { color: var(--text-muted); font-size: .8rem; margin-bottom: 4px; }
.wa-link {
    color: #22a366 !important;
    text-decoration: none;
    font-size: .82rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.wa-link:hover { text-decoration: underline; }

.action-btn {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1px solid transparent;
    background: transparent;
    display: inline-flex;
    align-items: center; justify-content: center;
    font-size: .85rem;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
}
.action-btn:hover { border-color: var(--border); background: var(--surface); }
.action-btn.view  { color: #3b82f6; }
.action-btn.edit  { color: #f59e0b; }
.action-btn.del   { color: #ef4444; }

.empty-state {
    text-align: center;
    padding: 56px 24px;
    color: var(--text-muted);
}
.empty-state i { font-size: 2rem; opacity: .2; display: block; margin-bottom: 10px; }
.empty-state p { margin: 0; font-size: .9rem; }

.modal-content {
    border: none;
    border-radius: 18px;
    box-shadow: 0 24px 60px rgba(13,27,46,.18);
    overflow: hidden;
}
.modal-header {
    background: var(--navy);
    border: none;
    padding: 18px 24px;
}
.modal-header .modal-title { color: #fff; font-weight: 700; font-size: 1rem; }
.modal-header .btn-close { filter: invert(1) brightness(2); }
.modal-body { padding: 24px; }
.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f4fa;
    font-size: .875rem;
}
.detail-row:last-child { border-bottom: none; }
.detail-label { color: var(--text-muted); }
.detail-value { font-weight: 600; color: var(--navy); text-align: right; max-width: 60%; }

@media (max-width: 768px) {
    .pegawai-hero { flex-direction: column; align-items: flex-start; gap: 18px; }
    .hero-title { font-size: 1.3rem; }
}
</style>
@endsection

@section('content')
<div class="pegawai-wrap">
    <div class="pegawai-hero mb-4">
        <div>
            <div class="hero-icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <h3 class="hero-title">Data Pegawai Tetap</h3>
            <p class="hero-sub">Kelola data staff internal dan gaji bulanan.</p>
        </div>
        <a href="{{ route('admin2.pegawai.create') }}" class="btn-add-pegawai">
            <i class="bi bi-plus-lg" style="font-size:.8rem;"></i> Tambah Pegawai
        </a>
    </div>

    <div class="pegawai-table-wrap">
        <div class="table-responsive">
            <table class="align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:5%;padding-left:24px;">#</th>
                        <th style="width:22%;">Nama Staff</th>
                        <th style="width:13%;">Posisi</th>
                        <th style="width:22%;">Inventaris</th>
                        <th style="width:13%;">Gaji Bulanan</th>
                        <th style="width:18%;">Domisili &amp; Kontak</th>
                        <th style="width:7%;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawais as $no => $p)
                    @php
                        $wa = preg_replace('/[^0-9]/','', $p->no_wa ?? '');
                        if(substr($wa,0,1)=='0') $wa='62'.substr($wa,1);
                    @endphp
                    <tr>
                        <td style="padding-left:24px;color:#94a3b8;font-size:.8rem;font-weight:600;">{{ $no + 1 }}</td>
                        <td><div class="name-cell">{{ $p->nama }}</div></td>
                        <td><span class="badge-role">{{ $p->role }}</span></td>
                        <td><span class="gear-text">{{ $p->gear ?? '—' }}</span></td>
                        <td><div class="rate-text"><span>Rp</span>{{ number_format($p->gaji,0,',','.') }}</div></td>
                        <td>
                            <div class="location-text"><i class="bi bi-geo-alt me-1" style="font-size:.75rem;"></i>{{ $p->domisili ?: '—' }}</div>
                            <a href="https://wa.me/{{ $wa }}" target="_blank" class="wa-link">
                                <i class="bi bi-whatsapp"></i>{{ $p->no_wa }}
                            </a>
                        </td>
                        <td style="text-align:center;">
                            <button type="button" class="action-btn view" title="Detail"
                                    data-bs-toggle="modal" data-bs-target="#pegawaiDetail{{ $p->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="{{ route('admin2.pegawai.edit', $p->id) }}" class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                                <form action="{{ route('admin2.pegawai.destroy', $p->id) }}" method="POST" style="display:inline;"
                                    data-confirm="Yakin hapus data pegawai ini?"
                                    data-confirm-type="delete"
                                    data-confirm-title="Hapus Pegawai">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn del" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="pegawaiDetail{{ $p->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Pegawai</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="detail-row"><span class="detail-label">Nama</span><span class="detail-value">{{ $p->nama }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Role</span><span class="detail-value">{{ $p->role }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Gear</span><span class="detail-value">{{ $p->gear ?: '—' }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Gaji Bulanan</span><span class="detail-value">Rp {{ number_format($p->gaji,0,',','.') }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Domisili</span><span class="detail-value">{{ $p->domisili ?: '—' }}</span></div>
                                    <div class="detail-row"><span class="detail-label">WhatsApp</span><span class="detail-value">{{ $p->no_wa ?: '—' }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-person-badge"></i>
                                <p>Belum ada data pegawai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
