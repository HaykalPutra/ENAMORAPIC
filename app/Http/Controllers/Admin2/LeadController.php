<?php

namespace App\Http\Controllers\Admin2;

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

        return view('admin2.leads.index', [
            'leads' => $leads,
            'stats' => $stats,
            'status' => $status,
            'search' => $search,
            'statuses' => Lead::STATUSES,
        ]);
    }

    public function show(Lead $lead)
    {
        $lead->load(['creator', 'customer', 'booking']);
        $activities = $lead->activities()
            ->with('creator')
            ->orderByDesc('created_at')
            ->get();

        return view('admin2.leads.show', [
            'lead' => $lead,
            'activities' => $activities,
            'statuses' => Lead::STATUSES,
        ]);
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

        return redirect()->route('admin2.leads.show', $lead->lead_id)->with('success', 'Aktivitas tersimpan.');
    }
}
