<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Installment;
use App\Models\Opportunity;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $student = $user->studentProfile()->with(['counselor', 'applications.opportunity'])->first();

            $reminders = $student
                ? collect()
                    ->concat(Installment::dueOrOverdue()->with('application.student')->whereHas('application', fn ($q) => $q->where('student_id', $student->id))->get()->map->reminderLine())
                    ->concat(Application::pendingTravelClearance()->with('student')->where('student_id', $student->id)->get()->map->reminderLine())
                : collect();

            return view('students.show', compact('student', 'reminders'));
        }

        if ($user->isPartner()) {
            $students = Student::where('partner_id', $user->id)->with('applications.opportunity')->latest()->get();
            $opportunities = Opportunity::where('type', 'study_abroad')->orderBy('name')->get();

            return view('students.partner', compact('students', 'opportunities'));
        }

        $students = $user->hasBackOfficeAccess()
            ? Student::with(['counselor', 'partner', 'applications.opportunity'])->latest()->get()
            : Student::where('counselor_id', $user->id)->with('applications.opportunity')->latest()->get();

        $unassigned = $user->hasBackOfficeAccess() || $user->isCounselor()
            ? Student::whereNull('counselor_id')->whereNull('partner_id')->get()
            : collect();

        $opportunities = Opportunity::orderBy('name')->get();

        $reminders = $user->isCounselor()
            ? collect()
                ->concat(Installment::dueOrOverdue()->with('application.student')->whereHas('application.student', fn ($q) => $q->where('counselor_id', $user->id))->get()->map->reminderLine())
                ->concat(Application::pendingTravelClearance()->with('student')->whereHas('student', fn ($q) => $q->where('counselor_id', $user->id))->get()->map->reminderLine())
            : collect();

        return view('students.index', compact('students', 'unassigned', 'opportunities', 'reminders'));
    }

    public function detail(Student $student)
    {
        $user = Auth::user();

        if (! $user->hasBackOfficeAccess() && $student->counselor_id !== $user->id) {
            abort(403);
        }

        $student->load(['counselor', 'partner', 'applications.opportunity', 'applications.installments', 'applications.clearance']);
        $opportunities = Opportunity::orderBy('name')->get();

        return view('students.detail', compact('student', 'opportunities'));
    }

    public function claim(Request $request, Student $student)
    {
        if (! Auth::user()->isCounselor() && ! Auth::user()->hasBackOfficeAccess()) {
            abort(403);
        }

        $data = $request->validate([
            'opportunity_id' => ['nullable', 'exists:opportunities,id'],
        ]);

        $student->update(['counselor_id' => Auth::id()]);

        if (! empty($data['opportunity_id'])) {
            $student->applications()->create(['opportunity_id' => $data['opportunity_id']]);
        }

        ActivityLog::record('student.claimed', "Claimed student \"{$student->name}\"");

        return redirect()->route('students.index')->with('status', 'Student assigned.');
    }

    public function registerPartnerStudent(Request $request)
    {
        if (! Auth::user()->isPartner()) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'opportunity_id' => ['required', 'exists:opportunities,id'],
        ]);

        $student = Student::create([
            'partner_id' => Auth::id(),
            'name' => $data['name'],
        ]);

        $student->applications()->create(['opportunity_id' => $data['opportunity_id']]);

        ActivityLog::record('student.registered', "Registered student \"{$data['name']}\"");

        return redirect()->route('students.index')->with('status', 'Student registered.');
    }
}
