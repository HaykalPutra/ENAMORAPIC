<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Enamorapic</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e293b; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px 0; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1e293b; color: white; padding: 10px 8px; text-align: left; font-size: 9pt; }
        td { padding: 8px; border-bottom: 1px solid #eee; font-size: 9pt; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .text-right { text-align: right; }
        .badge-lunas { color: #198754; font-weight: bold; }
        .badge-dp    { color: #fd7e14; font-weight: bold; }
        .badge-batal { color: #dc3545; font-weight: bold; }
        .total-row td { background: #1e293b !important; color: white; font-weight: bold; padding: 12px 8px; }
        .total-row-2 td { background: #243348 !important; color: white; font-weight: bold; padding: 10px 8px; }
        .total-row-3 td { background: #2b3d57 !important; color: white; font-weight: bold; padding: 10px 8px; }
        .dicoret { text-decoration: line-through; color: #999; }
        .footer { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN ENAMORAPIC</h2>
        @php
            $bulanNames = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            $periodeLabel = request('bulan') ? ($bulanNames[request('bulan')] . ' ') : '';
            $periodeLabel .= request('tahun', date('Y'));
        @endphp
        <p style="margin:4px 0;">Periode: {{ $periodeLabel }}</p>
        <p style="margin:0;color:#666;font-size:9pt;">Dicetak: {{ date('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Tgl Booking</th>
                <th>Nama Client</th>
                <th>Paket</th>
                <th>Tgl Acara</th>
                <th>Status</th>
                <th class="text-right">Total</th>
                <th class="text-right">Masuk</th>
                <th class="text-right">Sisa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $no => $b)
            @php $namaPaket = $b->details->first()?->paket?->nama_paket ?? '-'; @endphp
            <tr>
                <td style="text-align:center;">{{ $no+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tgl_booking)->format('d/m/Y') }}</td>
                <td><b>{{ $b->nama_client }}</b></td>
                <td>{{ $namaPaket }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') }}</td>
                <td>
                    @if($b->job_status=='Batal')
                        <span class="badge-batal">BATAL</span>
                    @elseif($b->job_status=='Selesai')
                        <span class="badge-lunas">SELESAI</span>
                    @else
                        <span>{{ $b->job_status }}</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($b->job_status=='Batal')
                        <span class="dicoret">Rp {{ number_format((float) $b->total_transaksi,0,',','.') }}</span>
                    @else
                        Rp {{ number_format((float) $b->total_transaksi,0,',','.') }}
                    @endif
                </td>
                <td class="text-right">Rp {{ number_format((int) $b->nominal_masuk,0,',','.') }}</td>
                <td class="text-right">Rp {{ number_format((int) $b->sisa_tagihan,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="8" class="text-right">TOTAL PENDAPATAN MASUK</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan,0,',','.') }}</td>
            </tr>
            <tr class="total-row-2">
                <td colspan="8" class="text-right">TOTAL MASUK DARI DP</td>
                <td class="text-right">Rp {{ number_format($totalPendapatanDp,0,',','.') }}</td>
            </tr>
            <tr class="total-row-3">
                <td colspan="8" class="text-right">TOTAL ESTIMASI BOOKING</td>
                <td class="text-right">Rp {{ number_format($totalEstimasi,0,',','.') }}</td>
            </tr>
            <tr class="total-row-3">
                <td colspan="8" class="text-right">TOTAL SISA TAGIHAN</td>
                <td class="text-right">Rp {{ number_format($totalSisa,0,',','.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Bandung, {{ now()->format('d F Y') }}</p>
        <br><br><br>
        <p>( Sekretaris Enamorapic )</p>
    </div>
</body>
</html>
