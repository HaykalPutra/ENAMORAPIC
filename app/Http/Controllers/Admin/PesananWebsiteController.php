<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananWebsiteController extends Controller
{
    public function index()
    {
        $pesanans = DB::table('pesanan_website')
            ->leftJoin('paket','pesanan_website.paket_id','=','paket.paket_id')
            ->select('pesanan_website.*','paket.nama_paket')
            ->orderByRaw("FIELD(pesanan_website.status,'pending','done')")
            ->orderByDesc('pesanan_website.created_at')
            ->paginate(20);

        $jumlahPending     = DB::table('pesanan_website')->where('status','pending')->count();
        $jumlahPesananBaru = $jumlahPending;

        return view('admin.pesanan-website.index', compact(
            'pesanans','jumlahPending','jumlahPesananBaru'
        ));
    }

    /**
     * Tandai pesanan sebagai done - sama dengan proses_status.php
     */
    public function markDone($id)
    {
        DB::table('pesanan_website')
            ->where('pesanan_id', $id)
            ->update(['status' => 'done']);

        return redirect()->route('admin.pesanan-website.index')
                         ->with('success','Pesanan berhasil ditandai selesai!');
    }
}
