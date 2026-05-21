@extends('layouts.admin2')
@section('title','Detail Wedding Organizer')
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
            <i class="bi bi-people" style="color:#d4af37; margin-right:10px;"></i>Detail Wedding Organizer
        </h4>
        <p class="text-muted mb-0">Informasi partner WO</p>
    </div>
    <a href="{{ route('admin2.wo.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="card" style="border-radius:16px;border:1px solid #e6edf7;box-shadow:0 10px 24px rgba(21,33,54,0.08);">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><strong>Nama WO:</strong> {{ $wo->nama_wo }}</div>
            <div class="col-md-6"><strong>PIC:</strong> {{ $wo->pic_name ?? '-' }}</div>
            <div class="col-md-6"><strong>WhatsApp:</strong> {{ $wo->no_wa }}</div>
            <div class="col-md-6"><strong>Email:</strong> {{ $wo->email ?? '-' }}</div>
            <div class="col-md-6"><strong>Instagram:</strong> {{ $wo->instagram ?? '-' }}</div>
            <div class="col-md-6"><strong>Status:</strong>
                <span class="badge bg-{{ $badgeMap[$wo->status] ?? 'secondary' }}">
                    {{ $statuses[$wo->status] ?? $wo->status }}
                </span>
            </div>
            <div class="col-md-12"><strong>Alamat:</strong> {{ $wo->alamat ?? '-' }}</div>
            <div class="col-md-12"><strong>Catatan:</strong> {{ $wo->catatan ?? '-' }}</div>
        </div>
    </div>
</div>
@endsection
