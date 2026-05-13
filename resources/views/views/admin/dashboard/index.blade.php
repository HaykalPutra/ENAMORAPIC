@extends('layouts.admin')
@section('title','Dashboard CEO')
@section('styles')
<style>
.page-enter{animation:fadeInUp 0.8s cubic-bezier(0.2,0.8,0.2,1);animation-fill-mode:both;opacity:0;}
@keyframes fadeInUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
.stat-card{border-radius:16px;padding:24px;transition:all 0.3s ease;border:none;color:white;position:relative;overflow:hidden;height:100%;box-shadow:0 4px 15px rgba(0,0,0,0.1);}
.stat-card::before{content:'';position:absolute;top:-50%;right:-50%;width:200%;height:200%;background:radial-gradient(circle,rgba(255,255,255,0.15) 0%,transparent 70%);opacity:0;transition:opacity 0.5s;}
.stat-card:hover::before{opacity:1;}
.stat-card:hover{transform:translateY(-5px);box-shadow:0 15px 30px rgba(0,0,0,0.2);}
.stat-card h2{font-size:2.2rem;font-weight:700;margin:10px 0 0 0;}
.stat-card p{opacity:0.9;margin:0;font-size:0.9rem;text-transform:uppercase;letter-spacing:1px;font-weight:600;}
.stat-icon{position:absolute;right:20px;top:20px;font-size:2.5rem;opacity:0.2;}
.chart-card{border-radius:16px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.05);background:white;height:100%;}
.chart-container{position:relative;height:300px;width:100%;}
.recent-booking{transition:all 0.2s ease;border-left:4px solid transparent;background:#f8f9fa;}
.recent-booking:hover{background:#fff;border-left-color:#667eea;box-shadow:0 2px 10px rgba(0,0,0,0.05);}
.badge-custom{padding:6px 12px;border-radius:20px;font-size:0.75rem;}
</style>
@endsection

@section('content')
<div class="page-enter">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard Overview</h3>
            <p class="text-muted mb-0">Selamat datang kembali, {{ Auth::user()->nama_lengkap }}! 👋</p>
        </div>
        <div class="text-end d-none d-md-block">
            <small class="text-muted d-block">Hari ini</small>
            <strong class="text-dark">{{ now()->locale('id')->isoFormat('D MMMM Y') }}</strong>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
                <i class="fas fa-calendar-check stat-icon"></i>
                <p>Booking Bulan Ini</p>
                <h2>{{ $totalBulanIni }}</h2>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);">
                <i class="fas fa-clock stat-icon"></i>
                <p>Menunggu Konfirmasi</p>
                <h2>{{ $totalPending }}</h2>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);">
                <i class="fas fa-money-bill-wave stat-icon"></i>
                <p>Revenue Bulan Ini</p>
                <h2 class="fs-3">Rp {{ number_format($revenueBulanIni/1000000,1) }}jt</h2>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);">
                <i class="fas fa-check-circle stat-icon"></i>
                <p>Total Selesai</p>
                <h2>{{ $totalSelesai }}</h2>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card chart-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-chart-line text-primary me-2"></i>Trend Revenue (6 Bulan Terakhir)</h5>
                    <div class="chart-container"><canvas id="revenueChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card chart-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fas fa-bell text-warning me-2"></i>Booking Terbaru</h5>
                    <div style="height:300px;overflow-y:auto;padding-right:5px;">
                        @forelse($bookingTerbaru as $b)
                        @php $bc=match($b->status_acara){'Selesai'=>'success','Batal'=>'danger','Confirmed','Booked'=>'primary',default=>'warning'}; @endphp
                        <div class="recent-booking p-3 mb-2 rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div style="max-width:60%;">
                                    <strong class="d-block text-dark text-truncate">{{ $b->nama_client }}</strong>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') }}</small>
                                </div>
                                <span class="badge badge-custom bg-{{ $bc }}">{{ $b->status_acara }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-5">Belum ada booking terbaru</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card chart-card">
                <div class="card-body">
                    <h5 class="fw-bold text-dark mb-4"><i class="fas fa-chart-bar text-success me-2"></i>Performa Tahun Ini (Jan - Des {{ now()->year }})</h5>
                    <div class="chart-container" style="height:350px;"><canvas id="yearChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card chart-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 text-dark">Status Pembayaran</h5>
                    <div class="chart-container" style="height:250px;"><canvas id="paymentChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card chart-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 text-dark">Paket Terpopuler</h5>
                    <div style="height:250px;overflow-y:auto;">
                        @php $colors=['primary','success','info','warning','danger'];$i=0; @endphp
                        @forelse($paketPopuler as $p)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <strong class="text-dark small">{{ $p->nama_paket }}</strong>
                            <span class="badge bg-{{ $colors[$i%5] }} rounded-pill px-3">{{ $p->total }} Booked</span>
                        </div>
                        @php $i++; @endphp
                        @empty
                        <p class="text-muted text-center py-4">Belum ada data paket.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const months6=@json($months6),revenues6=@json($revenues6),yearlyData=@json($yearlyData);
const lunas={{ $lunas }},dp={{ $dp }};

new Chart(document.getElementById('revenueChart'),{type:'line',data:{labels:months6,datasets:[{label:'Revenue',data:revenues6,borderColor:'#667eea',backgroundColor:'rgba(102,126,234,0.1)',tension:0.4,fill:true,borderWidth:2,pointRadius:4,pointBackgroundColor:'#fff',pointBorderColor:'#667eea'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>'Rp '+new Intl.NumberFormat('id-ID').format(c.parsed.y)}}},scales:{y:{beginAtZero:true,ticks:{callback:v=>(v/1000000).toFixed(1)+'jt'}},x:{grid:{display:false}}}}});

new Chart(document.getElementById('yearChart'),{type:'bar',data:{labels:['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],datasets:[{label:'Pendapatan {{ now()->year }}',data:yearlyData,backgroundColor:'#4facfe',borderRadius:6,barPercentage:0.6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>'Rp '+new Intl.NumberFormat('id-ID').format(c.parsed.y)}}},scales:{y:{beginAtZero:true,grid:{color:'#f0f0f0'},ticks:{callback:v=>(v/1000000).toFixed(0)+'jt'}},x:{grid:{display:false}}}}});

new Chart(document.getElementById('paymentChart'),{type:'doughnut',data:{labels:['Lunas','DP'],datasets:[{data:[lunas,dp],backgroundColor:['#43e97b','#f5576c'],borderWidth:2,borderColor:'#fff'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'right',labels:{boxWidth:15,usePointStyle:true}}},layout:{padding:20}}});
</script>
@endsection
