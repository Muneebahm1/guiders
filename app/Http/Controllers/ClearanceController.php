<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClearanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,processing_team,counselor');
    }

    public function show(Application $application)
    {
        $this->authorizeApplication($application);

        $application->load(['student', 'opportunity', 'installments']);
        $clearance = $application->clearance ?? $application->clearance()->create([]);

        return view('clearance.show', compact('application', 'clearance'));
    }

    public function update(Request $request, Application $application)
    {
        $this->authorizeApplication($application);

        $data = $request->validate([
            'tuition_cleared' => ['nullable', 'boolean'],
            'service_dues_cleared' => ['nullable', 'boolean'],
            'remarks' => ['nullable', 'string'],
            'signed_off' => ['nullable', 'boolean'],
        ]);

        $clearance = $application->clearance ?? $application->clearance()->create([]);

        $signingOff = ! empty($data['signed_off']);

        if ($signingOff && ! $clearance->allInstallmentsPaid()) {
            return back()->withErrors(['signed_off' => 'All installments must be paid before sign-off.'])->withInput();
        }

        $clearance->tuition_cleared = ! empty($data['tuition_cleared']);
        $clearance->service_dues_cleared = ! empty($data['service_dues_cleared']);
        $clearance->remarks = $data['remarks'] ?? null;
        $clearance->signed_off = $signingOff;
        $clearance->signed_off_by_id = $signingOff ? Auth::id() : null;
        $clearance->signed_off_at = $signingOff ? now() : null;
        $clearance->save();

        ActivityLog::record('clearance.updated', "Updated dues clearance for \"{$application->student->name}\"".($signingOff ? ' (signed off)' : ''));

        return redirect()->route('clearance.show', $application)->with('status', 'Clearance updated.');
    }

    private function authorizeApplication(Application $application): void
    {
        $user = Auth::user();

        if ($user->hasBackOfficeAccess() || $application->student->counselor_id === $user->id) {
            return;
        }

        abort(403);
    }
}
