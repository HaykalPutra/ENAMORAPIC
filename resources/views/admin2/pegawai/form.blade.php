@extends('layouts.admin2')
@section('title', isset($pegawai) ? 'Edit Pegawai' : 'Tambah Pegawai')
@section('hideTopbarTitle', '1')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin2.pegawai.index') }}" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0"><i class="bi bi-person-badge me-2 text-primary"></i>{{ isset($pegawai) ? 'Edit Data Pegawai' : 'Tambah Pegawai Tetap' }}</h5>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form action="{{ isset($pegawai) ? route('admin2.pegawai.update', $pegawai->id) : route('admin2.pegawai.store') }}" method="POST">
                @csrf
                @if(isset($pegawai)) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $pegawai->nama ?? '') }}" placeholder="Masukan nama pegawai" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small text-muted">Role / Jabatan</label>
                        <select name="role" class="form-select" required>
                            <option value="" disabled>-- Pilih Role --</option>
                            @foreach(['Photographer','Videographer','Admin','Editor','Assistant'] as $r)
                            <option value="{{ $r }}" {{ old('role', $pegawai->role ?? '') == $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small text-muted">Gaji Bulanan (Rp)</label>
                        <input type="number" name="gaji" class="form-control" value="{{ old('gaji', $pegawai->gaji ?? '') }}" placeholder="3500000" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Gear / Inventaris (Opsional)</label>
                    <textarea name="gear" class="form-control" rows="2" placeholder="Alat yang dipegang pegawai ini">{{ old('gear', $pegawai->gear ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Domisili</label>
                    <input type="text" name="domisili" class="form-control" value="{{ old('domisili', $pegawai->domisili ?? '') }}" placeholder="Kota tempat tinggal">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Nomor WhatsApp</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">+62</span>
                        <input type="text" name="no_wa" class="form-control" value="{{ old('no_wa', $pegawai->no_wa ?? '') }}" placeholder="812xxxxxxx">
                    </div>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-dark fw-bold py-2">
                        <i class="fas fa-save me-1"></i> {{ isset($pegawai) ? 'Update Pegawai' : 'Simpan Data Pegawai' }}
                    </button>
                    <a href="{{ route('admin2.pegawai.index') }}" class="btn btn-light border">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
