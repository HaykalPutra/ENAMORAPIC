<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Enamora</title>
    <style>
        body{font-family:sans-serif;font-size:10pt;color:#333;}
        .header{text-align:center;margin-bottom:20px;border-bottom:2px solid #333;padding-bottom:10px;}
        .header h2{margin:0 0 5px 0;}
        table{width:100%;border-collapse:collapse;margin-top:10px;}
        th{background-color:#f2f2f2;padding:10px;border:1px solid #999;text-align:left;font-size:9pt;}
        td{padding:8px;border:1px solid #999;font-size:9pt;}
        .text-end{text-align:right;}
        .badge-lunas{color:#198754;font-weight:bold;text-transform:uppercase;}
        .badge-dp{color:#fd7e14;font-weight:bold;text-transform:uppercase;}
        .badge-batal{color:#dc3545;font-weight:bold;text-transform:uppercase;}
        .total-row{background-color:#333;color:#fff;font-weight:bold;}
        .dicoret{text-decoration:line-through;color:#999;}
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PEMASUKAN ENAMORAPIC</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($tglDari)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tglSampai)->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tgl Transaksi</th>
                <th>Nama Client</th>
                <th>Tgl Acara</th>
                <th>Status</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanData as $no => $d)
            <tr>
                <td style="text-align:center;">{{ $no+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($d->tgl_booking)->format('d/m/Y') }}</td>
                <td><b>{{ $d->nama_client }}</b></td>
                <td>{{ \Carbon\Carbon::parse($d->tgl_acara)->format('d M Y') }}</td>
                <td>
                    @if($d->status_acara=='Batal')
                        <span class="badge-batal">DIBATALKAN</span>
                    @elseif($d->status_pembayaran=='Lunas')
                        <span class="badge-lunas">LUNAS</span>
                    @else
                        <span class="badge-dp">DP</span>
                    @endif
                </td>
                <td class="text-end">
                    @if($d->status_acara=='Batal')
                        <span class="dicoret">Rp {{ number_format($d->total_transaksi,0,',','.') }}</span>
                    @else
                        Rp {{ number_format($d->total_transaksi,0,',','.') }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-end" style="padding:10px">TOTAL PENDAPATAN BERSIH</td>
                <td class="text-end" style="padding:10px">Rp {{ number_format($totalOmzet,0,',','.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top:30px;text-align:right;">
        <p>Bandung, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</p>
        <br><br><br>
        <p>( Admin Keuangan )</p>
    </div>
</body>
</html>
