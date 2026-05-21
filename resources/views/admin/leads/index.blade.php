@extends('layouts.admin')
@section('title','Leads')
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
            <i class="bi bi-kanban" style="color:#d4af37; margin-right:10px;"></i>CRM-lite Leads
        </h4>
        <p class="text-muted mb-0">Pantau prospek sampai menjadi booking</p>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach($statuses as $key => $label)
        <div class="col-md-2 col-sm-4">
            <div class="card h-100" style="border-radius:14px;border:1px solid #e9eef7;box-shadow:0 6px 16px rgba(21,33,54,0.06);">
                <div class="card-body">
                    <div class="text-uppercase" style="letter-spacing:.1em;font-size:.68rem;color:#6b7a96;">{{ $label }}</div>
                    <div class="fw-bold" style="font-size:1.4rem;color:#1a2332;">{{ number_format($stats[$key] ?? 0) }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card mb-4" style="border-radius:16px;border:1px solid #e6edf7;box-shadow:0 10px 24px rgba(21,33,54,0.08);">
    <div class="card-header bg-white" style="border-top-left-radius:16px;border-top-right-radius:16px;">
        <div class="d-flex align-items-center justify-content-between">
            <span class="fw-semibold">Tambah Lead Baru</span>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.leads.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">WhatsApp</label>
                <input type="text" name="no_wa" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sumber Lead</label>
                <input type="text" name="sumber" class="form-control" placeholder="Instagram, Referral, ...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Follow-up</label>
                <input type="date" name="next_follow_up_at" class="form-control">
            </div>
            <div class="col-md-12">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Simpan Lead
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card" style="border-radius:16px;border:1px solid #e6edf7;box-shadow:0 10px 24px rgba(21,33,54,0.08);">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama atau WA">
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
                        <th>Nama</th>
                        <th>WA</th>
                        <th>Status</th>
                        <th>Last Contact</th>
                        <th>Next Follow-up</th>
                        <th>Aktivitas</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr>
                            <td class="fw-semibold">{{ $lead->nama }}</td>
                            <td>{{ $lead->no_wa }}</td>
                            <td>
                                <span class="badge bg-{{ $badgeMap[$lead->status] ?? 'secondary' }}">
                                    {{ $statuses[$lead->status] ?? $lead->status }}
                                </span>
                            </td>
                            <td>{{ $lead->last_contact_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td>{{ $lead->next_follow_up_at?->format('d M Y') ?? '-' }}</td>
                            <td>{{ number_format($lead->activities_count ?? 0) }}</td>
                            <td>
                                <a href="{{ route('admin.leads.show', $lead->lead_id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada lead.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            {{ $leads->links() }}
        </div>
    </div>
</div>
@endsection
