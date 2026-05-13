@extends('layouts.admin')
@section('title', isset($paket) ? 'Edit Paket Foto' : 'Tambah Paket Foto')
@section('hideTopbarTitle', '1')

@section('styles')
<style>
.paket-form-wrap {
    max-width: 980px;
    margin: 0 auto;
}

.paket-form-card {
    border: 0;
    border-radius: 18px;
    box-shadow: 0 14px 34px rgba(17, 24, 39, 0.08);
}

.paket-form-card .card-body {
    padding: 30px !important;
}

.paket-form-card .form-control,
.paket-form-card .form-select {
    border-radius: 12px;
    padding-top: 0.72rem;
    padding-bottom: 0.72rem;
}

.paket-form-card .form-control:focus,
.paket-form-card .form-select:focus {
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
}

.paket-section-note {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 12px;
    font-size: 0.88rem;
    color: #64748b;
}
</style>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.paket.index') }}" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-camera-reels me-2 text-primary"></i>{{ isset($paket) ? 'Edit Paket Foto' : 'Tambah Paket Foto Baru' }}</h4>
        <p class="text-muted mb-0 small">{{ isset($paket) ? 'Update data paket foto' : 'Tambah paket foto baru ke katalog' }}</p>
    </div>
</div>

<div class="paket-form-wrap">
    <div class="card paket-form-card">
        <div class="card-body p-4">
                <form action="{{ isset($paket) ? route('admin.paket.update', $paket->paket_id) : route('admin.paket.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($paket)) @method('PUT') @endif

                    @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" class="form-control form-control-lg"
                               value="{{ old('nama_paket', $paket->nama_paket ?? '') }}"
                               placeholder="Contoh: Wedding Fullday Photo & Video" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select form-select-lg">
                                @foreach(['Wedding','Pre-Wedding','Engagement','Other'] as $kat)
                                <option value="{{ $kat }}" {{ old('kategori', $paket->kategori ?? '') == $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control form-control-lg"
                                   value="{{ old('harga', $paket->harga ?? '') }}"
                                   placeholder="5000000" min="0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Detail Paket Foto <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="4"
                                  placeholder="Contoh: Durasi 5 jam&#10;1 fotografer&#10;150+ edited photos&#10;All file digital&#10;Free konsultasi konsep" required>{{ old('deskripsi', $paket->deskripsi ?? '') }}</textarea>
                        <div class="paket-section-note mt-2">
                            Isi per baris (atau pisahkan pakai koma) agar setiap detail tampil terpisah di halaman customer.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Galeri Media (URL per baris) <span class="text-muted">(Opsional)</span></label>
                        <textarea name="media_urls" class="form-control" rows="4"
                                  placeholder="https://contoh.com/foto1.jpg&#10;https://contoh.com/foto2.jpg&#10;https://contoh.com/video-preview.mp4">{{ old('media_urls', $paket->media_urls ?? '') }}</textarea>
                        <small class="text-muted">Isi link FOTO per baris (disarankan 3-8 foto). Satu baris = satu media.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Video Utama (1 Video) <span class="text-muted">(Opsional)</span></label>
                        <input type="text" name="video_url" class="form-control"
                                   value="{{ old('video_url', $paket->video_url ?? '') }}"
                               placeholder="https://www.youtube.com/watch?v=3PUKVqYLOWE">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Gambar Paket <span class="text-muted">(Opsional)</span></label>
                        @if(isset($paket) && $paket->gambar)
                        <div class="mb-2">
                            @php
                                $curr = public_path('assets/images/'.$paket->gambar);
                                $fallback = 'https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800';
                            @endphp
                            <img src="{{ file_exists($curr) ? asset('assets/images/'.$paket->gambar) : $fallback }}" height="80"
                                 class="rounded shadow-sm" onerror="this.src='https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800'">
                            <small class="text-muted ms-2">Gambar saat ini</small>
                        </div>
                        @endif
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, JPEG, PNG, WEBP. Maks 2MB. Biarkan kosong jika tidak ingin mengganti.</small>
                    </div>

                    <div class="d-flex gap-2 justify-content-end pt-2 border-top">
                        <a href="{{ route('admin.paket.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>{{ isset($paket) ? 'Update Paket Foto' : 'Simpan Paket Foto' }}
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection
