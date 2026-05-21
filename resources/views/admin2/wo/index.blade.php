@extends('layouts.admin2')
@section('title','Wedding Organizer')
@section('hideTopbarTitle', '1')

@section('content')
@php
    $badgeMap = [
        'active' => 'success',
        'inactive' => 'secondary',
    ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1a2332;">
            <i class="bi bi-people" style="color:#d4af37; margin-right:10px;"></i>Data Wedding Organizer
        </h4>
        <p class="text-muted mb-0">Daftar partner WO (read-only)</p>
    </div>
</div>

<div class="card" style="border-radius:16px;border:1px solid #e6edf7;box-shadow:0 10px 24px rgba(21,33,54,0.08);">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama, PIC, atau WA">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100" type="submit">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead style="background:#f6f9ff;">
                    <tr>
                        <th>Nama WO</th>
                        <th>PIC</th>
                        <th>WA</th>
                        <th>Instagram</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wos as $wo)
                        <tr>
                            <td class="fw-semibold">{{ $wo->nama_wo }}</td>
                            <td>{{ $wo->pic_name ?? '-' }}</td>
                            <td>{{ $wo->no_wa }}</td>
                            <td>{{ $wo->instagram ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $badgeMap[$wo->status] ?? 'secondary' }}">
                                    {{ $statuses[$wo->status] ?? $wo->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin2.wo.show', $wo->wo_id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data WO.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            {{ $wos->links() }}
        </div>
    </div>
</div>
@endsection
