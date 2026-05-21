<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query()->withCount('activities');

        $status = $request->get('status');
        if (!empty($status)) {
            $query->where('status', $status);
        }

        $search = trim((string) $request->get('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('no_wa', 'like', '%' . $search . '%');
            });
        }

        $leads = $query->orderByDesc('updated_at')
            ->orderByDesc('lead_id')
            ->paginate(20)
            ->withQueryString();

        $stats = Lead::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.leads.index', [
            'leads' => $leads,
            'stats' => $stats,
            'status' => $status,
            'search' => $search,
            'statuses' => Lead::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'no_wa' => ['required', 'string', 'max:20'],
            'sumber' => ['nullable', 'string', 'max:80'],
            'catatan' => ['nullable', 'string'],
            'next_follow_up_at' => ['nullable', 'date'],
        ]);

        $data['no_wa'] = $this->normalizeWhatsApp($data['no_wa']);
        $data['status'] = 'prospect';
        $data['created_by'] = Auth::id();

        Lead::create($data);

        return redirect()->route('admin.leads.index')->with('success', 'Lead berhasil ditambahkan.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['creator', 'customer', 'booking']);
        $activities = $lead->activities()
            ->with('creator')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.leads.show', [
            'lead' => $lead,
            'activities' => $activities,
            'statuses' => Lead::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Lead::STATUSES))],
            'next_follow_up_at' => ['nullable', 'date'],
            'catatan' => ['nullable', 'string'],
        ]);

        $lead->update($data);

        return redirect()->route('admin.leads.show', $lead->lead_id)->with('success', 'Status lead diperbarui.');
    }

    public function storeActivity(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'activity_type' => ['required', 'in:wa,call,meeting,note'],
            'activity_note' => ['required', 'string'],
        ]);

        $data['created_by'] = Auth::id();
        $data['lead_id'] = $lead->lead_id;

        LeadActivity::create($data);

        $lead->update([
            'last_contact_at' => now(),
        ]);

        return redirect()->route('admin.leads.show', $lead->lead_id)->with('success', 'Aktivitas tersimpan.');
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
