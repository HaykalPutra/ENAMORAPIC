@extends('layouts.admin2')
@section('title', 'Detail Customer')
@section('hideTopbarTitle', '1')

@section('styles')
<style>
.profile-card {
    background:#ffffff;
    border:1px solid #e7edf8;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(11,23,54,.07);
}
.profile-avatar { width:80px; height:80px; border-radius:50%; background:#0b1736; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:1.8rem; margin:0 auto 16px; }
.stat-box { text-align:center; }
.stat-value { font-size:1.5rem; font-weight:800; color:#1a2f6a; margin-bottom:4px; }
.stat-label { font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; color:#8a99b3; font-weight:700; }
.booking-card {
    background:#ffffff;
    border:1px solid #e7edf8;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(11,23,54,.07);
}
</style>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin2.customer.index') }}" class="btn" style="background:#f0f4f9;color:#1a2f6a;border:1px solid #e0e8f3;" title="Kembali">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h3 class="fw-bold mb-0 text-dark"><i class="bi bi-person-vcard me-2" style="color:#d4af37;"></i>Detail Customer</h3>
        <p class="text-secondary mb-0 small">Riwayat booking, paket, dan progres termin pembayaran</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="profile-card p-4">
            <div class="profile-avatar">
                {{ strtoupper(substr($customer->nama_client, 0, 2)) }}
            </div>
            <h5 class="fw-bold text-center" style="color:#1a2f6a;">{{ $customer->nama_client }}</h5>
            @php
                $wa = preg_replace('/[^0-9]/','', $customer->no_wa ?? '');
                if(substr($wa,0,1)=='0') $wa='62'.substr($wa,1);
            @endphp
            <a href="https://wa.me/{{ $wa }}" target="_blank" class="btn btn-success btn-sm w-100 mt-3">
                <i class="bi bi-whatsapp me-1"></i>{{ $customer->no_wa }}
            </a>
            <hr style="border-color:#e7edf8;margin:16px 0;">
            <div class="row g-3 mt-0">
                <div class="col-6 stat-box">
                    <div class="stat-value">{{ $customer->bookings->count() }}</div>
                    <div class="stat-label">Total Booking</div>
                </div>
                <div class="col-6 stat-box">
                    <div class="stat-value" style="font-size:1rem;">Rp {{ number_format($customer->bookings->where('job_status','!=','Batal')->sum('total_transaksi')/1000000,1) }}jt</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="booking-card">
            <div class="px-4 py-3 border-bottom" style="background:#f7f9fd;">
                <div class="fw-bold" style="color:#1a2f6a;">Riwayat Booking</div>
                <div class="text-secondary" style="font-size:.82rem;">Pantau status booking dan progres pembayaran termin.</div>
            </div>
            <div class="p-0">
                @forelse($customer->bookings as $b)
                @php
                    $bc = match($b->job_status){ 'Selesai'=>'success','Batal'=>'danger','Confirmed','Booked'=>'primary',default=>'warning' };
                    $paidTerms = $b->paymentTerms->where('payment_status', 'paid')->count();
                    $allTerms = $b->paymentTerms->count();
                    $secondTerm = $b->paymentTerms->firstWhere('term_number', 2);
                    $isSecondTermUnpaid = $secondTerm && $secondTerm->payment_status !== 'paid';
                    $paketList = $b->details->pluck('paket.nama_paket')->filter()->values();
                    $followUpMessage = "Halo Kak {$customer->nama_client}, kami follow up pembayaran Termin 2 untuk Booking #{$b->booking_id}. Mohon konfirmasi ya kak. Terima kasih.";
                    $paymentLink = route('payment.show', $b->booking_id);
                    $paymentLinkMessage = "Halo Kak {$customer->nama_client}, berikut link pembayaran booking #{$b->booking_id}: {$paymentLink}. Silakan lanjutkan pembayaran termin berikutnya ya kak.";
                @endphp
                <div class="p-4 border-bottom" style="border-color:#e7edf8;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong style="color:#1a2f6a;">Booking #{{ $b->booking_id }}</strong>
                            <div class="text-secondary small mt-1">
                                <i class="bi bi-calendar3 me-1"></i>
                                Acara: {{ \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') }}
                            </div>
                            <div class="small mt-2">
                                <span class="text-secondary d-block mb-1">Paket Dipilih:</span>
                                @forelse($paketList as $namaPaket)
                                    <span class="badge me-1 mb-1" style="background:#e8f0fd;color:#34517e;border:1px solid #d9e6ff;">{{ $namaPaket }}</span>
                                @empty
                                    <span class="text-secondary">Paket N/A</span>
                                @endforelse
                            </div>
                            <div class="small mt-2">
                                <span class="badge" style="background:#e8f0fd;color:#34517e;border:1px solid #d9e6ff;">Termin Dibayar: {{ $paidTerms }}/{{ $allTerms }}</span>
                                @if($isSecondTermUnpaid)
                                    <a href="https://wa.me/{{ $wa }}?text={{ urlencode($followUpMessage) }}" target="_blank" class="btn btn-sm btn-outline-warning ms-2">
                                        <i class="bi bi-whatsapp me-1"></i>Follow Up Termin 2
                                    </a>
                                @endif
                            </div>
                            <div class="small mt-2 d-flex flex-wrap gap-2">
                                <a href="{{ route('admin2.customer.terms', [$customer->customer_id, $b->booking_id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Lihat Termin (Read-only)
                                </a>
                                <a href="https://wa.me/{{ $wa }}?text={{ urlencode($paymentLinkMessage) }}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="bi bi-send me-1"></i>Kirim Link Pembayaran
                                </a>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $bc }} mb-2" style="display:inline-block;">{{ $b->job_status }}</span><br>
                            <strong style="color:#1a2f6a;font-size:1.05rem;">Rp {{ number_format($b->total_transaksi,0,',','.') }}</strong>
                        </div>
                    </div>
                    @if($b->paymentTerms->count())
                    <div class="mt-3 pt-3" style="border-top:1px solid #e7edf8;">
                        <div class="text-secondary small mb-2 fw-bold">Detail Termin:</div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($b->paymentTerms->sortBy('term_number') as $term)
                                <span class="badge {{ $term->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    T{{ $term->term_number }}: Rp {{ number_format((float)$term->term_amount,0,',','.') }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-5 text-secondary">
                    <i class="bi bi-calendar-x fs-1 d-block mb-2" style="opacity:.15;"></i>
                    Belum ada riwayat booking.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
