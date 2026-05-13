<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = Paket::orderByDesc('paket_id')->get();
        return view('admin.paket.index', compact('pakets'));
    }

    public function create()
    {
        // Dimuat via AJAX ke dalam modal
        return view('admin.paket.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kategori'   => 'required',
            'harga'      => 'required|numeric',
            'deskripsi'  => 'required|string|min:10',
            'media_urls' => 'nullable|string',
            'video_url'  => 'nullable|string|max:500',
        ]);

        // Logika gambar - sama persis dengan paket_proses.php
        $gambar = $this->prosesGambar($request, null);

        Paket::create($this->buildPaketPayload($request, $gambar));

        return redirect()->route('admin.paket.index')
                         ->with('success','Paket berhasil ditambahkan!');
    }

    public function edit(Paket $paket)
    {
        
        return view('admin.paket.form', compact('paket'));
    }

    public function update(Request $request, Paket $paket)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kategori'   => 'required',
            'harga'      => 'required|numeric',
            'deskripsi'  => 'required|string|min:10',
            'media_urls' => 'nullable|string',
            'video_url'  => 'nullable|string|max:500',
        ]);

        $gambar = $this->prosesGambar($request, $paket->gambar);

        $paket->update($this->buildPaketPayload($request, $gambar));

        return redirect()->route('admin.paket.index')
                         ->with('success','Paket berhasil diupdate!');
    }

    private function buildPaketPayload(Request $request, string $gambar): array
    {
        $payload = [
            'nama_paket' => $request->nama_paket,
            'kategori'   => $request->kategori,
            'harga'      => $request->harga,
            'deskripsi'  => $request->deskripsi,
            'gambar'     => $gambar,
        ];

        if (Schema::hasColumn('paket', 'media_urls')) {
            $payload['media_urls'] = $this->normalizeMediaUrls($request->media_urls);
        }

        if (Schema::hasColumn('paket', 'video_url')) {
            $payload['video_url'] = $this->normalizeSingleUrl($request->video_url);
        }

        return $payload;
    }

    public function destroy(Paket $paket)
    {
        $paket->delete();
        return redirect()->route('admin.paket.index')
                         ->with('success','Paket berhasil dihapus!');
    }

    
    private function prosesGambar(Request $request, ?string $gambarLama): string
    {
        // Jika ada file yang diupload
        if ($request->hasFile('gambar')) {
            $file     = $request->file('gambar');
            $filename = now()->format('dmYHis') . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);
            return $filename;
        }


        if (!empty($gambarLama)) {
            return $gambarLama;
        }

        return match($request->kategori) {
            'Wedding'    => 'wedding.jpeg',
            'Prewedding' => 'prewedd.jpeg',
            default      => 'engagement.jpeg',
        };
    }

    private function normalizeMediaUrls(?string $raw): ?string
    {
        if (!$raw) {
            return null;
        }

        $lines = preg_split('/\r\n|\r|\n/', $raw) ?: [];
        $clean = collect($lines)
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->unique()
            ->values();

        return $clean->isEmpty() ? null : $clean->implode(PHP_EOL);
    }

    private function normalizeSingleUrl(?string $url): ?string
    {
        $value = trim((string) $url);
        return $value === '' ? null : $value;
    }
}
