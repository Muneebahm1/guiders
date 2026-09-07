<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\CompanySettings;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,processing_team,counselor');
    }

    public function index()
    {
        $user = Auth::user();

        $invoices = $user->hasBackOfficeAccess()
            ? Invoice::with(['student', 'creator'])->latest()->get()
            : Invoice::with(['student', 'creator'])->where('created_by_id', $user->id)->latest()->get();

        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $students = Student::orderBy('name')->get();
        $selectedStudent = $request->filled('student_id')
            ? Student::with('applications.opportunity')->find($request->student_id)
            : null;
        $selectedApplicationId = $request->integer('application_id') ?: null;
        $company = CompanySettings::current();

        return view('invoices.create', compact('students', 'selectedStudent', 'selectedApplicationId', 'company'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'application_id' => ['nullable', 'exists:applications,id'],
            'issued_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issued_date'],
            'currency' => ['required', 'string', 'max:10'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'terms' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item' => ['required', 'string', 'max:255'],
            'items.*.currency' => ['required', 'string', 'max:10'],
            'items.*.currency_value' => ['required', 'numeric', 'min:0'],
            'items.*.exchange_rate' => ['required', 'numeric', 'min:0.01'],
        ]);

        $subtotal = 0;
        $items = [];

        foreach ($data['items'] as $i => $row) {
            $amount = round($row['currency_value'] * $row['exchange_rate'], 2);
            $subtotal += $amount;

            $items[] = [
                'item' => $row['item'],
                'currency' => $row['currency'],
                'currency_value' => $row['currency_value'],
                'exchange_rate' => $row['exchange_rate'],
                'amount' => $amount,
                'sort_order' => $i,
            ];
        }

        $tax = $data['tax'] ?? 0;
        $total = $subtotal + $tax;

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'student_id' => $data['student_id'],
            'application_id' => $data['application_id'] ?? null,
            'created_by_id' => Auth::id(),
            'issued_date' => $data['issued_date'],
            'due_date' => $data['due_date'] ?? null,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'currency' => $data['currency'],
            'notes' => $data['notes'] ?? CompanySettings::current()?->notes,
            'terms' => $data['terms'] ?? CompanySettings::current()?->terms,
        ]);

        $invoice->items()->createMany($items);

        ActivityLog::record('invoice.created', "Generated invoice {$invoice->invoice_number} for \"{$invoice->student->name}\"");

        return redirect()->route('invoices.show', $invoice)->with('status', 'Invoice generated.');
    }

    public function show(Invoice $invoice)
    {
        $user = Auth::user();

        if (! $user->hasBackOfficeAccess() && $invoice->created_by_id !== $user->id) {
            abort(403);
        }

        $invoice->load(['items', 'student', 'creator', 'application.opportunity']);
        $company = CompanySettings::current();

        return view('invoices.show', compact('invoice', 'company'));
    }
}
