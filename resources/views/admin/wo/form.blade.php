@extends('layouts.admin')
@section('title', isset($wo) ? 'Edit Wedding Organizer' : 'Tambah Wedding Organizer')
@section('hideTopbarTitle', '1')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.wo.index') }}" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">{{ isset($wo) ? 'Edit Wedding Organizer' : 'Tambah Wedding Organizer' }}</h4>
        <p class="text-muted mb-0 small">Kelola data partner WO</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card" style="border-radius:16px;border:1px solid #e6edf7;box-shadow:0 10px 24px rgba(21,33,54,0.08);">
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ isset($wo) ? route('admin.wo.update', $wo->wo_id) : route('admin.wo.store') }}">
                    @csrf
                    @if(isset($wo)) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama WO</label>
                            <input type="text" name="nama_wo" class="form-control" value="{{ old('nama_wo', $wo->nama_wo ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">PIC / Contact Person</label>
                            <input type="text" name="pic_name" class="form-control" value="{{ old('pic_name', $wo->pic_name ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="no_wa" class="form-control" value="{{ old('no_wa', $wo->no_wa ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $wo->email ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Instagram</label>
                            <input type="text" name="instagram" class="form-control" value="{{ old('instagram', $wo->instagram ?? '') }}" placeholder="@nama_wo">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Kerja Sama</label>
                            <select name="status" class="form-select" required>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $wo->status ?? 'active') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $wo->alamat ?? '') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan Kerja Sama</label>
                            <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $wo->catatan ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('admin.wo.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> {{ isset($wo) ? 'Update WO' : 'Simpan WO' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
