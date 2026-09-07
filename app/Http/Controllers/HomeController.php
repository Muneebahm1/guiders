<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\FollowUp;
use App\Models\Installment;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            return redirect()->route('students.index');
        }

        if ($user->isPartner()) {
            return redirect()->route('students.index');
        }

        if ($user->hasBackOfficeAccess()) {
            $stats = [
                'Open opportunities' => Opportunity::where('deadline', '>=', now()->startOfDay())->count(),
                'Closing within 14 days' => Opportunity::whereBetween('deadline', [now()->startOfDay(), now()->addDays(14)])->count(),
                'Papers in review' => Paper::whereIn('status', ['Submitted', 'Under Review', 'Revisions Requested'])->count(),
                'Visa cases in progress' => Application::whereNotIn('visa_stage', ['Not Started', 'Approved', 'Rejected'])->count(),
                'Total leads' => Lead::count(),
                'Total follow-ups' => FollowUp::count(),
            ];

            $reminders = collect()
                ->concat(Installment::dueOrOverdue()->with('application.student')->get()->map->reminderLine())
                ->concat(Application::pendingTravelClearance()->with('student')->get()->map->reminderLine());

            return view('home', compact('stats', 'reminders'));
        }

        // Counselors redirect directly to leads page
        return redirect()->route('leads.index');
    }

    public function ping(Request $request)
    {
        return response()->json([
            'message' => 'pong',
            'user' => Auth::user()->only('id', 'name', 'email'),
            'time' => now()->toDateTimeString(),
        ]);
    }
}
