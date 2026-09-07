<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstallmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,processing_team,counselor');
    }

    public function store(Request $request, Application $application)
    {
        $this->authorizeApplication($application);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'due_date' => ['required', 'date'],
        ]);

        $application->installments()->create($data);
        $application->recalculateFeeStatus()->save();

        ActivityLog::record('installment.created', "Added installment \"{$data['title']}\" for \"{$application->student->name}\"");

        return redirect()->back()->with('status', 'Installment added.');
    }

    public function markPaid(Installment $installment)
    {
        $application = $installment->application;
        $this->authorizeApplication($application);

        $installment->update([
            'paid_date' => now(),
            'paid_amount' => $installment->amount,
        ]);

        $application->recalculateFeeStatus()->save();

        ActivityLog::record('installment.paid', "Marked installment \"{$installment->title}\" paid for \"{$application->student->name}\"");

        return redirect()->back()->with('status', 'Installment marked paid.');
    }

    public function destroy(Installment $installment)
    {
        $application = $installment->application;
        $this->authorizeApplication($application);

        $installment->delete();
        $application->recalculateFeeStatus()->save();

        ActivityLog::record('installment.deleted', "Deleted installment \"{$installment->title}\" for \"{$application->student->name}\"");

        return redirect()->back()->with('status', 'Installment removed.');
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
