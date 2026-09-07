<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:counselor')->except(['all']);
        $this->middleware('role:admin')->only(['all']);
    }

    public function index()
    {
        $leads = Lead::where('counselor_id', Auth::id())->orderBy('name')->get();
        $followUps = FollowUp::where('counselor_id', Auth::id())->orderBy('due_date')->get();
        $followUpsByLead = $followUps->groupBy('lead_id');
        $unlinked = $followUpsByLead->get(null, collect());

        return view('followups.index', compact('leads', 'followUpsByLead', 'unlinked'));
    }

    public function all()
    {
        $followUps = FollowUp::with(['counselor', 'lead'])->orderBy('due_date')->get();

        return view('followups.all', compact('followUps'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lead_id' => ['nullable', 'exists:leads,id'],
            'student_name' => ['required_without:lead_id', 'nullable', 'string', 'max:255'],
            'action' => ['required', 'in:'.implode(',', FollowUp::ACTIONS)],
            'due_date' => ['required', 'date'],
        ]);

        $lead = null;

        if (! empty($data['lead_id'])) {
            $lead = Lead::where('counselor_id', Auth::id())->findOrFail($data['lead_id']);
        }

        FollowUp::create([
            'counselor_id' => Auth::id(),
            'lead_id' => $lead?->id,
            'student_name' => $data['student_name'] ?: $lead->name,
            'action' => $data['action'],
            'due_date' => $data['due_date'],
        ]);

        ActivityLog::record('followup.created', "Added follow-up for \"{$data['student_name']}\"");

        return redirect()->route('followups.index')->with('status', 'Follow-up added.');
    }

    public function cycleAction(Request $request, FollowUp $followUp)
    {
        if ($followUp->counselor_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'due_date' => ['nullable', 'date'],
        ]);

        $update = ['action' => $followUp->nextAction()];

        if (! empty($data['due_date'])) {
            $update['due_date'] = $data['due_date'];
        }

        $followUp->update($update);

        return response()->json([
            'action' => $followUp->action,
            'due_date' => $followUp->due_date->toDateString(),
        ]);
    }
}
