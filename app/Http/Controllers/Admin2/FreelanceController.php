<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Freelance;
use Illuminate\Http\Request;

class FreelanceController extends Controller
{
    public function index()
    {
        $freelances = Freelance::orderBy('nama')->get();
        return view('admin2.freelance.index', compact('freelances'));
    }

    public function create()
    {
        return view('admin2.freelance.form', ['freelance' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'     => 'required|string|max:100',
            'gear'     => 'nullable|string|max:255',
            'role'     => 'required|in:Photographer,Videographer,Assistant,Editor',
            'harga'    => 'required|numeric|min:0',
            'domisili' => 'nullable|string|max:100',
            'no_wa'    => 'nullable|string|max:20',
        ]);
        Freelance::create($data);
        return redirect()->route('admin2.freelance.index')->with('success', 'Freelance berhasil ditambahkan.');
    }

    public function edit(Freelance $freelance)
    {
        return view('admin2.freelance.form', compact('freelance'));
    }

    public function update(Request $request, Freelance $freelance)
    {
        $data = $request->validate([
            'nama'     => 'required|string|max:100',
            'gear'     => 'nullable|string|max:255',
            'role'     => 'required|in:Photographer,Videographer,Assistant,Editor',
            'harga'    => 'required|numeric|min:0',
            'domisili' => 'nullable|string|max:100',
            'no_wa'    => 'nullable|string|max:20',
        ]);
        $freelance->update($data);
        return redirect()->route('admin2.freelance.index')->with('success', 'Data freelance berhasil diupdate.');
    }

    public function destroy(Freelance $freelance)
    {
        $freelance->delete();
        return redirect()->route('admin2.freelance.index')->with('success', 'Freelance berhasil dihapus.');
    }
}
