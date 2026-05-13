<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::orderBy('nama')->get();
        return view('admin2.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('admin2.pegawai.form', ['pegawai' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'     => 'required|string|max:100',
            'gear'     => 'nullable|string|max:255',
            'role'     => 'required|in:Photographer,Videographer,Admin,Editor,Assistant',
            'gaji'     => 'required|numeric|min:0',
            'domisili' => 'nullable|string|max:100',
            'no_wa'    => 'nullable|string|max:20',
        ]);
        Pegawai::create($data);
        return redirect()->route('admin2.pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        return view('admin2.pegawai.form', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'nama'     => 'required|string|max:100',
            'gear'     => 'nullable|string|max:255',
            'role'     => 'required|in:Photographer,Videographer,Admin,Editor,Assistant',
            'gaji'     => 'required|numeric|min:0',
            'domisili' => 'nullable|string|max:100',
            'no_wa'    => 'nullable|string|max:20',
        ]);
        $pegawai->update($data);
        return redirect()->route('admin2.pegawai.index')->with('success', 'Data pegawai berhasil diupdate.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return redirect()->route('admin2.pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
