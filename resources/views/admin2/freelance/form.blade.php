@extends('layouts.admin2')
@section('title', isset($freelance) ? 'Edit Freelance' : 'Tambah Freelance')
@section('hideTopbarTitle', '1')

@section('styles')
<style>
.freelance-form-card {
    border: 0;
    border-radius: 16px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
}

.freelance-form-card .card-body {
    padding: 28px !important;
}

.freelance-form-card .form-control,
.freelance-form-card .form-select {
    border-radius: 10px;
    border: 1px solid #dbe3ef;
}

.freelance-form-card .form-control:focus,
.freelance-form-card .form-select:focus {
    box-shadow: 0 0 0 .2rem rgba(25,118,210,.12);
    border-color: #86b7fe;
}
</style>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin2.freelance.index') }}" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h5 class="fw-bold mb-0">
        <i class="bi bi-camera-reels me-2 text-primary"></i>{{ isset($freelance) ? 'Edit Data Freelance' : 'Tambah Freelance Baru' }}
    </h5>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card freelance-form-card">
            <div class="card-body p-4">
            @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form action="{{ isset($freelance) ? route('admin2.freelance.update', $freelance->id) : route('admin2.freelance.store') }}" method="POST">
                @csrf
                @if(isset($freelance)) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $freelance->nama ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Role</label>
                    <select name="role" class="form-select">
                        @foreach(['Photographer','Videographer','Assistant','Editor'] as $r)
                        <option value="{{ $r }}" {{ old('role', $freelance->role ?? '') == $r ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Gear Kamera</label>
                    <textarea name="gear" class="form-control" rows="2" placeholder="Sony A7III, Lensa 35mm...">{{ old('gear', $freelance->gear ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Harga Per Job (Rp)</label>
                    <input type="number" name="harga" class="form-control" value="{{ old('harga', $freelance->harga ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Domisili</label>
                    <input type="text" name="domisili" class="form-control" value="{{ old('domisili', $freelance->domisili ?? '') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Nomor WhatsApp</label>
                    <input type="text" name="no_wa" class="form-control" value="{{ old('no_wa', $freelance->no_wa ?? '') }}" placeholder="628xxxxx">
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-dark fw-bold py-2">
                        <i class="fas fa-save me-1"></i> {{ isset($freelance) ? 'Update' : 'Simpan' }} Data
                    </button>
                    <a href="{{ route('admin2.freelance.index') }}" class="btn btn-light border">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection
