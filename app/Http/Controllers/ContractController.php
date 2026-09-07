<?php

namespace App\Http\Controllers;

use App\Mail\ContractGeneratedMail;
use App\Models\ActivityLog;
use App\Models\CompanySettings;
use App\Models\Contract;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,processing_team,counselor');
    }

    public function index()
    {
        $user = Auth::user();

        $contracts = $user->hasBackOfficeAccess()
            ? Contract::with(['student', 'creator'])->latest()->get()
            : Contract::with(['student', 'creator'])->where('created_by_id', $user->id)->latest()->get();

        return view('contracts.index', compact('contracts'));
    }

    public function create(Request $request)
    {
        $students = Student::orderBy('name')->get();
        $selectedStudent = $request->filled('student_id')
            ? Student::find($request->student_id)
            : null;
        $company = CompanySettings::current();

        return view('contracts.create', compact('students', 'selectedStudent', 'company'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'cnic' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'client_email' => ['nullable', 'email', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'university' => ['nullable', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:255'],
            'service_type' => ['nullable', 'string', 'max:255'],
            'service_detail' => ['required', 'string'],
            'intake' => ['nullable', 'string', 'max:100'],
            'total_fee' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'clauses' => ['nullable', 'array'],
            'clauses.*' => ['string'],
            'custom_terms' => ['nullable', 'string'],
            'requirements' => ['nullable', 'array'],
            'requirements.*.text' => ['nullable', 'string', 'max:255'],
            'installments' => ['nullable', 'array'],
            'installments.*.description' => ['nullable', 'string', 'max:255'],
            'installments.*.amount' => ['nullable', 'numeric', 'min:0'],
            'installments.*.due_date' => ['nullable', 'date'],
        ]);

        $requirements = collect($data['requirements'] ?? [])
            ->filter(fn ($row) => filled($row['text'] ?? null))
            ->map(fn ($row) => ['text' => $row['text'], 'required' => (bool) ($row['required'] ?? false)])
            ->values()
            ->all();

        $contract = Contract::create([
            'student_id' => $data['student_id'],
            'created_by_id' => Auth::id(),
            'guardian_name' => $data['guardian_name'] ?? null,
            'cnic' => $data['cnic'] ?? null,
            'phone' => $data['phone'] ?? null,
            'client_email' => $data['client_email'] ?? null,
            'country' => $data['country'] ?? null,
            'university' => $data['university'] ?? null,
            'university_tbd' => $request->boolean('university_tbd'),
            'course' => $data['course'] ?? null,
            'service_type' => $data['service_type'] ?? null,
            'service_detail' => $data['service_detail'],
            'intake' => $data['intake'] ?? null,
            'total_fee' => $data['total_fee'] ?? null,
            'currency' => $data['currency'],
            'clauses' => $data['clauses'] ?? [],
            'custom_terms' => $data['custom_terms'] ?? null,
            'requirements' => $requirements,
        ]);

        foreach ($data['installments'] ?? [] as $i => $row) {
            if (blank($row['description'] ?? null) && blank($row['amount'] ?? null) && blank($row['due_date'] ?? null)) {
                continue;
            }

            $contract->installments()->create([
                'description' => $row['description'] ?? null,
                'amount' => $row['amount'] ?? 0,
                'due_date' => $row['due_date'] ?? null,
                'sort_order' => $i,
            ]);
        }

        ActivityLog::record('contract.created', "Created contract for \"{$contract->student->name}\"");

        $this->sendContractEmails($contract);

        return redirect()->route('contracts.show', $contract)->with('status', 'Contract generated.');
    }

    public function show(Contract $contract)
    {
        $user = Auth::user();

        if (! $user->hasBackOfficeAccess() && $contract->created_by_id !== $user->id) {
            abort(403);
        }

        $contract->load(['installments', 'student', 'creator']);
        $company = CompanySettings::current();

        return view('contracts.show', compact('contract', 'company'));
    }

    private function sendContractEmails(Contract $contract): void
    {
        $contract->loadMissing(['student', 'creator']);

        if ($contract->client_email) {
            try {
                Mail::to($contract->client_email)->send(new ContractGeneratedMail($contract, forClient: true));
            } catch (\Throwable $e) {
                Log::warning("Failed to send contract email to client: {$e->getMessage()}");
            }
        }

        if ($contract->creator?->email) {
            try {
                Mail::to($contract->creator->email)->send(new ContractGeneratedMail($contract, forClient: false));
            } catch (\Throwable $e) {
                Log::warning("Failed to send contract email to counselor: {$e->getMessage()}");
            }
        }
    }
}
