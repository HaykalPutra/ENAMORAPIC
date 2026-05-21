@extends('layouts.admin')
@section('title','Detail Lead')
@section('hideTopbarTitle', '1')

@section('content')
@php
    $badgeMap = [
        'prospect' => 'secondary',
        'negotiation' => 'warning',
        'booked' => 'primary',
        'completed' => 'success',
        'lost' => 'danger',
    ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1a2332;">
            <i class="bi bi-clipboard-data" style="color:#d4af37; margin-right:10px;"></i>Detail Lead
        </h4>
        <p class="text-muted mb-0">Pantau perkembangan prospek</p>
    </div>
    <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card" style="border-radius:16px;border:1px solid #e6edf7;box-shadow:0 10px 24px rgba(21,33,54,0.08);">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Info Lead</h5>
                <div class="mb-2"><strong>Nama:</strong> {{ $lead->nama }}</div>
                <div class="mb-2"><strong>WhatsApp:</strong> {{ $lead->no_wa }}</div>
                <div class="mb-2"><strong>Sumber:</strong> {{ $lead->sumber ?? '-' }}</div>
                <div class="mb-2"><strong>Status:</strong>
                    <span class="badge bg-{{ $badgeMap[$lead->status] ?? 'secondary' }}">
                        {{ $statuses[$lead->status] ?? $lead->status }}
                    </span>
                </div>
                <div class="mb-2"><strong>Last Contact:</strong> {{ $lead->last_contact_at?->format('d M Y H:i') ?? '-' }}</div>
                <div class="mb-2"><strong>Next Follow-up:</strong> {{ $lead->next_follow_up_at?->format('d M Y') ?? '-' }}</div>
                <div class="mb-2"><strong>Customer:</strong> {{ $lead->customer?->nama_client ?? '-' }}</div>
                <div class="mb-2"><strong>Booking:</strong> {{ $lead->booking_id ?? '-' }}</div>
                <div class="mb-2"><strong>Catatan:</strong> {{ $lead->catatan ?? '-' }}</div>
            </div>
        </div>

        <div class="card mt-4" style="border-radius:16px;border:1px solid #e6edf7;box-shadow:0 10px 24px rgba(21,33,54,0.08);">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Update Status</h6>
                <form method="POST" action="{{ route('admin.leads.status', $lead->lead_id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ $lead->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Follow-up berikutnya</label>
                        <input type="date" name="next_follow_up_at" class="form-control" value="{{ $lead->next_follow_up_at?->format('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3">{{ $lead->catatan }}</textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4" style="border-radius:16px;border:1px solid #e6edf7;box-shadow:0 10px 24px rgba(21,33,54,0.08);">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Tambah Aktivitas</h6>
                <form method="POST" action="{{ route('admin.leads.activities.store', $lead->lead_id) }}" class="row g-2">
                    @csrf
                    <div class="col-md-3">
                        <select name="activity_type" class="form-select" required>
                            <option value="wa">WhatsApp</option>
                            <option value="call">Call</option>
                            <option value="meeting">Meeting</option>
                            <option value="note">Catatan</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="activity_note" class="form-control" placeholder="Ringkasan aktivitas" required>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-outline-primary" type="submit">Simpan Aktivitas</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card" style="border-radius:16px;border:1px solid #e6edf7;box-shadow:0 10px 24px rgba(21,33,54,0.08);">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Riwayat Aktivitas</h6>
                @forelse($activities as $activity)
                    <div class="border rounded-3 p-3 mb-2" style="border-color:#eef2fb;">
                        <div class="d-flex justify-content-between">
                            <div class="fw-semibold text-capitalize">{{ $activity->activity_type }}</div>
                            <small class="text-muted">{{ $activity->created_at?->format('d M Y H:i') }}</small>
                        </div>
                        <div class="mt-2">{{ $activity->activity_note }}</div>
                        <small class="text-muted">Oleh: {{ $activity->creator?->nama_lengkap ?? '-' }}</small>
                    </div>
                @empty
                    <div class="text-muted">Belum ada aktivitas.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
