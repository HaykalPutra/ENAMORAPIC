<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\WeddingOrganizer;
use Illuminate\Http\Request;

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

        return view('admin2.wo.index', [
            'wos' => $wos,
            'status' => $status,
            'search' => $search,
            'statuses' => WeddingOrganizer::STATUSES,
        ]);
    }

    public function show(WeddingOrganizer $wo)
    {
        return view('admin2.wo.show', [
            'wo' => $wo,
            'statuses' => WeddingOrganizer::STATUSES,
        ]);
    }
}
