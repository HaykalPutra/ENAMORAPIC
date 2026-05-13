{{-- Partial view - dimuat via AJAX ke dalam modal --}}
<div class="p-4">
    <h5 class="mb-4 fw-bold">{{ isset($paket) ? 'Edit Paket' : 'Tambah Paket Baru' }}</h5>

    <form action="{{ isset($paket) ? route('admin.paket.update', $paket->paket_id) : route('admin.paket.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($paket)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label small fw-bold">Nama Paket</label>
            <input type="text" name="nama_paket" class="form-control"
                   value="{{ $paket->nama_paket ?? '' }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold">Kategori</label>
                <select name="kategori" class="form-select">
                    @foreach(['Wedding','Prewedding','Engagement','Other'] as $kat)
                    <option value="{{ $kat }}" {{ (($paket->kategori ?? '') == $kat) ? 'selected' : '' }}>
                        {{ $kat }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control"
                       value="{{ $paket->harga ?? '' }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Deskripsi & Benefit</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ $paket->deskripsi ?? '' }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Galeri Media (URL Foto, per baris)</label>
            <textarea name="media_urls" class="form-control" rows="4"
                      placeholder="https://contoh.com/foto1.jpg&#10;https://contoh.com/foto2.jpg">{{ $paket->media_urls ?? '' }}</textarea>
            <small class="text-muted">Isi beberapa foto. Satu baris = satu link foto.</small>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Video Utama (1 Video)</label>
            <input type="text" name="video_url" class="form-control"
                   value="{{ $paket->video_url ?? '' }}"
                   placeholder="https://contoh.com/highlight.mp4 atau link YouTube">
            <small class="text-muted">Tempel link video di kolom ini.</small>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Gambar Paket</label>
            @if(isset($paket) && $paket->gambar)
            <div class="mb-2">
                @php
                    $curr = public_path('assets/images/'.$paket->gambar);
                    $fallback = 'https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800';
                @endphp
                <img src="{{ file_exists($curr) ? asset('assets/images/'.$paket->gambar) : $fallback }}" height="60"
                     class="rounded" onerror="this.src='https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800'">
                <small class="text-muted ms-2">Gambar saat ini</small>
            </div>
            @endif
            <input type="file" name="gambar" class="form-control" accept="image/*">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
            @if(isset($paket))
            <input type="hidden" name="gambar_lama" value="{{ $paket->gambar }}">
            @endif
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i>Simpan Data
            </button>
        </div>
    </form>
</div>
