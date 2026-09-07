<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Opportunity;
use App\Models\OpportunitySession;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,processing_team')->only(['create', 'store', 'storeSession', 'destroySession']);
    }

    public function index(Request $request)
    {
        $term = $request->query('q', '');

        $opportunities = Opportunity::with('sessions')
            ->when($term, function ($query) use ($term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('country', 'like', "%{$term}%")
                        ->orWhere('requirements', 'like', "%{$term}%");
                });
            })
            ->orderBy('deadline')
            ->get();

        if ($request->ajax()) {
            return view('opportunities._rows', compact('opportunities'));
        }

        return view('opportunities.index', compact('opportunities', 'term'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:study_abroad,research_publication'],
            'deadline' => ['required', 'date'],
            'requirements' => ['nullable', 'string'],
            'official_link' => ['nullable', 'string', 'max:255'],
            'application_link' => ['nullable', 'string', 'max:255'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],
            'guidance' => ['nullable', 'string'],
        ]);

        Opportunity::create($data);

        ActivityLog::record('opportunity.created', "Added opportunity \"{$data['name']}\"");

        return redirect()->route('opportunities.index')->with('status', 'Opportunity added.');
    }

    public function storeSession(Request $request, Opportunity $opportunity)
    {
        $data = $request->validate([
            'session_name' => ['required', 'string', 'max:255'],
            'university' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'deadline' => ['nullable', 'date'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $opportunity->sessions()->create($data);

        ActivityLog::record('opportunity_session.created', "Added session \"{$data['session_name']}\" to opportunity \"{$opportunity->name}\"");

        return redirect()->route('opportunities.index')->with('status', 'Session added.');
    }

    public function destroySession(OpportunitySession $session)
    {
        $sessionName = $session->session_name;
        $opportunityName = $session->opportunity->name;
        $session->delete();

        ActivityLog::record('opportunity_session.deleted', "Deleted session \"{$sessionName}\" from opportunity \"{$opportunityName}\"");

        return redirect()->route('opportunities.index')->with('status', 'Session deleted.');
    }
}
