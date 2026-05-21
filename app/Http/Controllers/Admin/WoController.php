<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeddingOrganizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WoController extends Controller
{
    public function index(Request $request)
    {
        $query = WeddingOrganizer::query();

        $status = $request->get('status');
        if (!empty($status)) {
            $query->where('status', $status);
        }

        $search = trim((string) $request->get('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_wo', 'like', '%' . $search . '%')
                    ->orWhere('no_wa', 'like', '%' . $search . '%')
                    ->orWhere('pic_name', 'like', '%' . $search . '%');
            });
        }

        $wos = $query->orderByDesc('wo_id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.wo.index', [
            'wos' => $wos,
            'status' => $status,
            'search' => $search,
            'statuses' => WeddingOrganizer::STATUSES,
        ]);
    }

    public function create()
    {
        return view('admin.wo.form', [
            'statuses' => WeddingOrganizer::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);
        $data['no_wa'] = $this->normalizeWhatsApp($data['no_wa'] ?? '-');
        $data['created_by'] = Auth::id();

        WeddingOrganizer::create($data);

        return redirect()->route('admin.wo.index')->with('success', 'Data WO berhasil ditambahkan.');
    }

    public function show(WeddingOrganizer $wo)
    {
        return view('admin.wo.show', [
            'wo' => $wo,
            'statuses' => WeddingOrganizer::STATUSES,
        ]);
    }

    public function edit(WeddingOrganizer $wo)
    {
        return view('admin.wo.form', [
            'wo' => $wo,
            'statuses' => WeddingOrganizer::STATUSES,
        ]);
    }

    public function update(Request $request, WeddingOrganizer $wo)
    {
        $data = $this->validatePayload($request);
        $data['no_wa'] = $this->normalizeWhatsApp($data['no_wa'] ?? '-');

        $wo->update($data);

        return redirect()->route('admin.wo.index')->with('success', 'Data WO berhasil diperbarui.');
    }

    public function destroy(WeddingOrganizer $wo)
    {
        $wo->delete();

        return redirect()->route('admin.wo.index')->with('success', 'Data WO berhasil dihapus.');
    }

    private function validatePayload(Request $request): array
    {
        $rules = [
            'nama_wo' => ['required', 'string', 'max:120'],
            'pic_name' => ['nullable', 'string', 'max:120'],
            'no_wa' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:120'],
            'alamat' => ['nullable', 'string', 'max:200'],
            'instagram' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'in:' . implode(',', array_keys(WeddingOrganizer::STATUSES))],
            'catatan' => ['nullable', 'string'],
        ];

        return $request->validate($rules);
    }

    private function normalizeWhatsApp(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if ($digits === '') {
            return '-';
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (!str_starts_with($digits, '62')) {
            return '62' . $digits;
        }

        return $digits;
    }
}
