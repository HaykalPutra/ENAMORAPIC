<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Enamora</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a2332; padding-bottom: 12px; }
        .header h2 { margin: 0 0 5px 0; color: #1a2332; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1a2332; color: white; padding: 10px 8px; text-align: left; font-size: 9pt; }
        td { padding: 8px; border-bottom: 1px solid #eee; font-size: 9pt; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .text-right { text-align: right; }
        .badge-lunas { color: #198754; font-weight: bold; }
        .badge-dp    { color: #fd7e14; font-weight: bold; }
        .badge-batal { color: #dc3545; font-weight: bold; }
        .total-row td { background: #1a2332 !important; color: white; font-weight: bold; padding: 12px 8px; }
        .dicoret { text-decoration: line-through; color: #999; }
        .footer { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN ENAMORAPIC</h2>
        <p style="margin:4px 0;">Periode: {{ date('d/m/Y',strtotime(request('tgl_dari',date('Y-01-01')))) }} s/d {{ date('d/m/Y',strtotime(request('tgl_sampai',date('Y-12-31')))) }}</p>
        <p style="margin:0;color:#666;font-size:9pt;">Dicetak: {{ date('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Tgl Transaksi</th>
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
            <tr>
                <td style="text-align:center;">{{ $no+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tgl_booking)->format('d/m/Y') }}</td>
                <td><b>{{ $b->nama_client }}</b></td>
                <td>{{ $b->nama_paket }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tgl_acara)->format('d M Y') }}</td>
                <td>
                    @if($b->job_status=='Batal')
                        <span class="badge-batal">BATAL</span>
                    @elseif($b->status_pembayaran=='Lunas')
                        <span class="badge-lunas">LUNAS</span>
                    @else
                        <span class="badge-dp">DP</span>
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
                <td class="text-right">Rp {{ number_format($totalPendapatanMasuk,0,',','.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="8" class="text-right">TOTAL MASUK DARI DP</td>
                <td class="text-right">Rp {{ number_format($totalPendapatanDp,0,',','.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="8" class="text-right">TOTAL ESTIMASI BOOKING</td>
                <td class="text-right">Rp {{ number_format($totalEstimasi,0,',','.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="8" class="text-right">TOTAL SISA TAGIHAN</td>
                <td class="text-right">Rp {{ number_format($totalSisa,0,',','.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Bandung, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</p>
        <br><br><br>
        <p>( CEO Enamorapic )</p>
    </div>
</body>
</html>