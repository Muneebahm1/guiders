<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,processing_team,counselor');
    }

    public function store(Request $request, Student $student)
    {
        $this->authorizeStudent($student);

        $data = $request->validate([
            'opportunity_id' => ['nullable', 'exists:opportunities,id'],
        ]);

        $application = $student->applications()->create($data);

        ActivityLog::record('application.created', "Added application for \"{$student->name}\"".($application->opportunity ? " to \"{$application->opportunity->name}\"" : ''));

        return redirect()->back()->with('status', 'Application added.');
    }

    public function cycleAppStage(Application $application)
    {
        $this->authorizeApplication($application);

        $application->update(['app_stage' => $application->nextAppStage()]);

        ActivityLog::record('application.app_stage_changed', "\"{$application->student->name}\" application stage moved to {$application->app_stage}");

        return response()->json(['app_stage' => $application->app_stage]);
    }

    public function cycleVisaStage(Application $application)
    {
        $this->authorizeApplication($application);

        $next = $application->nextVisaStage();
        $update = ['visa_stage' => $next];

        if ($next === 'Submitted' && ! $application->visa_submission_date) {
            $update['visa_submission_date'] = now();
        }

        $application->update($update);

        ActivityLog::record('application.visa_stage_changed', "\"{$application->student->name}\" visa stage moved to {$application->visa_stage}");

        return response()->json([
            'visa_stage' => $application->visa_stage,
            'visa_submission_date' => $application->visa_submission_date?->toDateString(),
        ]);
    }

    public function cycleTravelStatus(Application $application)
    {
        $this->authorizeApplication($application);

        $next = $application->nextTravelStatus();

        if (in_array($next, ['Cleared to Travel', 'Travelled'], true) && ! $application->canTravel()) {
            return response()->json([
                'error' => 'Dues clearance must be signed off before this application can be marked ready to travel.',
            ], 422);
        }

        $update = ['travel_status' => $next];

        if ($next === 'Travelled' && ! $application->travel_date) {
            $update['travel_date'] = now();
        }

        $application->update($update);

        ActivityLog::record('application.travel_status_changed', "\"{$application->student->name}\" travel status moved to {$application->travel_status}");

        return response()->json([
            'travel_status' => $application->travel_status,
            'travel_date' => $application->travel_date?->toDateString(),
        ]);
    }

    public function destroy(Application $application)
    {
        if (! Auth::user()->hasBackOfficeAccess()) {
            abort(403);
        }

        $student = $application->student;
        $application->delete();

        ActivityLog::record('application.deleted', "Deleted an application for \"{$student->name}\"");

        return redirect()->back()->with('status', 'Application removed.');
    }

    private function authorizeStudent(Student $student): void
    {
        $user = Auth::user();

        if ($user->hasBackOfficeAccess() || $student->counselor_id === $user->id) {
            return;
        }

        abort(403);
    }

    private function authorizeApplication(Application $application): void
    {
        $this->authorizeStudent($application->student);
    }
}
