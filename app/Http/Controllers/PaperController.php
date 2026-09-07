<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Opportunity;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaperController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,processing_team,counselor');
    }

    public function index()
    {
        $user = Auth::user();

        $papers = $user->hasBackOfficeAccess()
            ? Paper::with(['counselor', 'opportunity'])->latest('submitted_date')->get()
            : Paper::where('counselor_id', $user->id)->with('opportunity')->latest('submitted_date')->get();

        $journals = Opportunity::where('type', 'research_publication')->orderBy('name')->get();

        return view('papers.index', compact('papers', 'journals'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->isCounselor()) {
            abort(403);
        }

        $data = $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'submitted_date' => ['required', 'date'],
        ]);

        $data['counselor_id'] = Auth::id();

        Paper::create($data);

        ActivityLog::record('paper.created', "Logged submission for \"{$data['author_name']}\"");

        return redirect()->route('papers.index')->with('status', 'Submission logged.');
    }

    public function cycleStatus(Paper $paper)
    {
        if (! Auth::user()->hasBackOfficeAccess() && $paper->counselor_id !== Auth::id()) {
            abort(403);
        }

        $paper->update(['status' => $paper->nextStatus()]);

        ActivityLog::record('paper.status_changed', "\"{$paper->author_name}\" submission status moved to {$paper->status}");

        return response()->json(['status' => $paper->status]);
    }
}
