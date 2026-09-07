<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\FollowUp;
use App\Models\Installment;
use App\Models\Lead;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function staffMonthly(Request $request)
    {
        $month = $request->filled('month') ? (string) $request->input('month') : now()->format('Y-m');
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $counselors = User::where('role', 'counselor')->orderBy('name')->get();

        if ($request->filled('counselor_id')) {
            $counselors = $counselors->where('id', $request->integer('counselor_id'))->values();
        }

        $rows = $counselors->map(function (User $counselor) use ($start, $end) {
            return [
                'counselor' => $counselor,
                'leads_added' => Lead::where('counselor_id', $counselor->id)->whereBetween('created_at', [$start, $end])->count(),
                'follow_ups_logged' => FollowUp::where('counselor_id', $counselor->id)->whereBetween('created_at', [$start, $end])->count(),
                'students_registered' => Student::where('counselor_id', $counselor->id)->whereBetween('created_at', [$start, $end])->count(),
                'applications_added' => Application::whereHas('student', fn ($q) => $q->where('counselor_id', $counselor->id))
                    ->whereBetween('applications.created_at', [$start, $end])->count(),
                'amount_collected' => Installment::whereHas('application.student', fn ($q) => $q->where('counselor_id', $counselor->id))
                    ->whereNotNull('paid_date')->whereBetween('paid_date', [$start, $end])->sum('paid_amount'),
            ];
        });

        $allCounselors = User::where('role', 'counselor')->orderBy('name')->get();

        return view('reports.staff', [
            'rows' => $rows,
            'month' => $start->format('Y-m'),
            'allCounselors' => $allCounselors,
            'selectedCounselorId' => $request->integer('counselor_id') ?: null,
        ]);
    }
}
