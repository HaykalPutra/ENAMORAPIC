@extends('layouts.admin')
@section('title','Paket Layanan')
@section('styles')
<style>
.page-enter{opacity:0;animation:fadeIn 0.8s ease forwards;}
@keyframes fadeIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.custom-card{border-radius:12px;border:none;box-shadow:0 2px 12px rgba(0,0,0,0.06);background:#fff;overflow:hidden;}
.action-bar-card{border-radius:12px;border:none;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;box-shadow:0 4px 20px rgba(102,126,234,0.3);}
.action-btn{width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;transition:all 0.2s ease;border:none;}
.action-btn:hover{transform:scale(1.1);}
.table thead th{background-color:#f8f9fa;border-bottom:2px solid #e9ecef;font-weight:600;color:#495057;padding:16px;}
.table tbody td{padding:16px;vertical-align:middle;}
</style>
@endsection

@section('content')
<div class="container-fluid page-enter py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-box-open text-primary me-2"></i>List Paket Layanan</h3>
            <p class="text-muted mb-0">Kelola katalog paket foto dan layanan</p>
        </div>
        <button type="button" id="btnTambah" class="btn btn-primary shadow-sm px-4 fw-bold">
            <i class="fas fa-plus me-2"></i>Tambah Paket
        </button>
    </div>

    <div class="card action-bar-card mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8 text-white">
                    <h5 class="mb-1 fw-bold">Cari Paket?</h5>
                    <p class="mb-0 opacity-75 small">Ketik nama paket atau kategori di sebelah kanan.</p>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-white text-muted ps-3"><i class="fas fa-search"></i></span>
                        <input id="searchPaket" class="form-control border-0 py-2" placeholder="Cari data...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablePaket">
                    <thead>
                        <tr>
                            <th class="ps-4" width="5%">No</th>
                            <th width="10%">Gambar</th>
                            <th width="20%">Nama Paket</th>
                            <th width="15%">Kategori</th>
                            <th width="15%">Harga</th>
                            <th width="25%">Deskripsi</th>
                            <th class="text-end pe-4" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pakets as $no => $p)
                        @php
                            $badgeClass = match($p->kategori) {
                                'Wedding'    => 'bg-primary',
                                'Prewedding' => 'bg-info',
                                default      => 'bg-success'
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $no+1 }}</td>
                            <td>
                                @php
                                    $fallbackThumb = 'https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800';
                                    $imgPath = public_path('assets/images/' . ($p->gambar ?? ''));
                                @endphp
                                <img src="{{ file_exists($imgPath) && $p->gambar ? asset('assets/images/'.$p->gambar) : $fallbackThumb }}"
                                     class="rounded shadow-sm" width="60" height="60"
                                     style="object-fit:cover;"
                                     onerror="this.src='https://images.unsplash.com/photo-1537633552985-df8429e8048b?w=800'">
                            </td>
                            <td><span class="fw-bold text-dark">{{ $p->nama_paket }}</span></td>
                            <td>
                                <span class="badge {{ $badgeClass }} bg-opacity-10 text-dark border border-light">
                                    {{ $p->kategori }}
                                </span>
                            </td>
                            <td class="fw-bold text-success">Rp {{ number_format($p->harga,0,',','.') }}</td>
                            <td class="text-muted small">{{ Str::limit(strip_tags($p->deskripsi),40) }}</td>
                            <td class="text-end pe-4">
                                <button class="action-btn bg-warning text-white me-1 btnEdit"
                                        data-id="{{ $p->paket_id }}" title="Edit">
                                    <i class="fas fa-pencil-alt" style="font-size:0.8rem;"></i>
                                </button>
                                <button class="action-btn bg-danger text-white btnHapus"
                                        data-id="{{ $p->paket_id }}" data-nama="{{ $p->nama_paket }}" title="Hapus">
                                    <i class="fas fa-trash-alt" style="font-size:0.8rem;"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-2x mb-2 opacity-50 d-block"></i>
                                Belum ada data paket.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH/EDIT --}}
<div class="modal fade" id="modalPaket" data-bs-backdrop="static" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
      <div id="formContainer">
        <div class="p-5 text-center"><div class="spinner-border text-primary"></div></div>
      </div>
    </div>
  </div>
</div>

{{-- FORM HAPUS (hidden) --}}
<form id="formHapus" action="" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){

    // Search
    $('#searchPaket').on('input', function(){
        const val = $(this).val().toLowerCase();
        $('#tablePaket tbody tr').filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
        });
    });

    // Tambah
    $('#btnTambah').click(function(){
        $('#formContainer').load('{{ route("admin.paket.create") }}', function(){
            $('#modalPaket').modal('show');
        });
    });

    // Edit
    $(document).on('click','.btnEdit',function(){
        const id = $(this).data('id');
        $('#formContainer').load(`/admin/paket/${id}/edit`, function(){
            $('#modalPaket').modal('show');
        });
    });

    // Hapus
    $(document).on('click','.btnHapus',function(){
        const id   = $(this).data('id');
        const nama = $(this).data('nama');
        Swal.fire({
            title: 'Hapus Paket?',
            text: `"${nama}" akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#adb5bd',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(r => {
            if(r.isConfirmed){
                $('#formHapus').attr('action', `/admin/paket/${id}`).submit();
            }
        });
    });

    // Flash sukses
    @if(session('success'))
    Swal.fire({icon:'success',title:'Berhasil',text:'{{ session("success") }}',timer:2000,showConfirmButton:false});
    @endif
});
</script>
@endsection
