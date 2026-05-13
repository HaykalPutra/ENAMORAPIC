@extends('layouts.admin')
@section('title', 'Detail Customer')
@section('hideTopbarTitle', '1')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.customer.index') }}" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-person-vcard me-2 text-primary"></i>Detail Customer</h4>
        <p class="text-muted mb-0 small">Riwayat booking lengkap</p>
    </div>
</div>

<div class="row g-4">
    {{-- PROFIL --}}
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body p-4">
                <div style="width:80px;height:80px;border-radius:50%;background:#1a2332;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.8rem;margin:0 auto 16px;">
                    {{ strtoupper(substr($customer->nama_client, 0, 2)) }}
                </div>
                <h5 class="fw-bold">{{ $customer->nama_client }}</h5>
                @php
                    $wa = preg_replace('/[^0-9]/','', $customer->no_wa ?? '');
                    if(substr($wa,0,1)=='0') $wa='62'.substr($wa,1);
                @endphp
                <a href="https://wa.me/{{ $wa }}" target="_blank" class="btn btn-success btn-sm mt-2">
                    <i class="bi bi-whatsapp me-1"></i>{{ $customer->no_wa }}
                </a>
                <hr>
                <div class="row text-center g-3 mt-1">
                    <div class="col-6">
                        <div class="fw-bold fs-4 text-primary">{{ $customer->bookings->count() }}</div>
                        <small class="text-muted">Total Booking</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold fs-5 text-success" style="font-size:0.9rem!important;">
                            Rp {{ number_format($customer->bookings->where('job_status','!=','Batal')->sum('total_transaksi')/1000000,1) }}jt
                        </div>
                        <small class="text-muted">Total Revenue</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIWAYAT BOOKING --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Riwayat Booking</div>
            <div class="card-body p-0">
                @forelse($customer->bookings as $b)
                @php
                    $bc = match($b->job_status){ 'Selesai'=>'success','Batal'=>'danger','Confirmed','Booked'=>'primary',default=>'warning' };
                    $paidTerms = $b->paymentTerms->where('payment_status', 'paid')->count();
                    $allTerms = $b->paymentTerms->count();
                    $secondTerm = $b->paymentTerms->firstWhere('term_number', 2);
                    $isSecondTermUnpaid = $secondTerm && $secondTerm->payment_status !== 'paid';
                    $paketList = $b->details->pluck('paket.nama_paket')->filter()->values();
                    $wa = preg_replace('/[^0-9]/','', $customer->no_wa ?? '');
                    if(substr($wa,0,1)=='0') $wa='62'.substr($wa,1);
                    $followUpMessage = "Halo Kak {$customer->nama_client}, kami follow up pembayaran Termin 2 untuk Booking #{$b->booking_id}. Mohon konfirmasi ya kak. Terima kasih.";
                @endphp
                <div class="p-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong class="text-dark">Booking #{{ $b->booking_id }}</strong>
                            <div class="text-muted small mt-1">
                                <i class="bi bi-calendar3 me-1"></i>
                                Acara: {{ \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') }}
                            </div>
                            <div class="small mt-2">
                                <span class="text-muted d-block mb-1">Paket Dipilih:</span>
                                @forelse($paketList as $namaPaket)
                                    <span class="badge bg-dark me-1 mb-1">{{ $namaPaket }}</span>
                                @empty
                                    <span class="text-muted">Paket N/A</span>
                                @endforelse
                            </div>
                            <div class="small mt-2">
                                <span class="badge bg-info text-dark">Termin Dibayar: {{ $paidTerms }}/{{ $allTerms }}</span>
                                @if($isSecondTermUnpaid)
                                    <a href="https://wa.me/{{ $wa }}?text={{ urlencode($followUpMessage) }}" target="_blank" class="btn btn-sm btn-outline-warning ms-2">
                                        <i class="bi bi-whatsapp me-1"></i>Follow Up Termin 2
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $bc }} mb-1">{{ $b->job_status }}</span><br>
                            <strong class="text-dark">Rp {{ number_format($b->total_transaksi,0,',','.') }}</strong>
                        </div>
                    </div>
                    @if($b->paymentTerms->count())
                    <div class="mt-3 pt-3 border-top">
                        <div class="text-muted small mb-2">Detail Termin:</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($b->paymentTerms->sortBy('term_number') as $term)
                                <span class="badge {{ $term->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                                    T{{ $term->term_number }}: Rp {{ number_format((float)$term->term_amount,0,',','.') }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>
                    Belum ada riwayat booking.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
