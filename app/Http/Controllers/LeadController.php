<?php

namespace App\Http\Controllers;

use App\Mail\LeadEmail;
use App\Models\ActivityLog;
use App\Models\CallLog;
use App\Models\Communication;
use App\Models\Lead;
use App\Models\LeadReminder;
use App\Models\Paper;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:counselor')->except(['all']);
        $this->middleware('role:admin')->only(['all']);
    }

    public function index()
    {
        $leads = Lead::where('counselor_id', Auth::id())
                     ->where('funnel_stage', 'Lead')
                     ->latest()
                     ->get();

        $totalLeads = Lead::where('counselor_id', Auth::id())->count();
        $convertedLeads = Lead::where('counselor_id', Auth::id())->where('funnel_stage', 'Converted')->count();
        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 1) : 0;

        $stats = [
            'My leads' => Lead::where('counselor_id', Auth::id())->where('funnel_stage', 'Lead')->count(),
            'New, not contacted' => Lead::where('counselor_id', Auth::id())->where('funnel_stage', 'Lead')->where('status', 'New')->count(),
            'My students' => Student::where('counselor_id', Auth::id())->count(),
            'My paper submissions' => Paper::where('counselor_id', Auth::id())->count(),
            'Conversion rate' => $conversionRate . '%',
            'Total calls made' => \App\Models\CallLog::where('counselor_id', Auth::id())->count(),
            'Documents uploaded' => \App\Models\LeadDocument::where('counselor_id', Auth::id())->count(),
        ];

        return view('leads.index', compact('leads', 'stats'));
    }

    public function all()
    {
        $leads = Lead::with('counselor')->orderBy('name')->get();

        return view('leads.all', compact('leads'));
    }

    public function followUps()
    {
        $leads = Lead::where('counselor_id', Auth::id())
                     ->where('funnel_stage', 'Follow-up')
                     ->latest()
                     ->get();

        $stats = [
            'My leads' => Lead::where('counselor_id', Auth::id())->where('funnel_stage', 'Follow-up')->count(),
            'New, not contacted' => Lead::where('counselor_id', Auth::id())->where('funnel_stage', 'Follow-up')->where('status', 'New')->count(),
            'My students' => Student::where('counselor_id', Auth::id())->count(),
            'My paper submissions' => Paper::where('counselor_id', Auth::id())->count(),
        ];

        return view('leads.follow-ups', compact('leads', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'source' => ['required', 'string', 'in:'.implode(',', Lead::SOURCES)],
        ]);

        Auth::user()->leads()->create($data);

        ActivityLog::record('lead.created', "Added lead \"{$data['name']}\"");

        return redirect()->route('leads.index')->with('status', 'Lead added.');
    }

    public function updateProfile(Request $request, Lead $lead)
    {
        $this->authorizeLead($lead);

        // Handle logging calls
        if ($request->has('log_call')) {
            CallLog::create([
                'lead_id' => $lead->id,
                'counselor_id' => Auth::id(),
                'call_date' => now(),
                'outcome' => $request->call_outcome ?? 'completed',
                'notes' => $request->call_notes,
                'duration_seconds' => $request->duration_seconds,
            ]);

            // Also update legacy call_log for backwards compatibility
            $callLog = $lead->call_log ? json_decode($lead->call_log, true) : [];
            $callLog[] = now()->format('Y-m-d');
            $lead->update(['call_log' => json_encode($callLog)]);

            // Log to communications table
            Communication::create([
                'lead_id' => $lead->id,
                'counselor_id' => Auth::id(),
                'type' => 'call',
                'direction' => 'outbound',
                'status' => 'sent',
                'sent_at' => now(),
                'content' => $request->call_notes,
            ]);

            ActivityLog::record('lead.call_logged', "Logged call for lead \"{$lead->name}\"");
            return response()->json(['saved' => true]);
        }

        // Handle logging activities (messages/visits)
        if ($request->has('log_activity')) {
            $kind = $request->log_activity;
            if ($kind === 'messages') {
                $lead->increment('messages');
                Communication::create([
                    'lead_id' => $lead->id,
                    'counselor_id' => Auth::id(),
                    'type' => 'whatsapp',
                    'direction' => 'outbound',
                    'status' => 'sent',
                    'sent_at' => now(),
                    'content' => $request->message_content,
                ]);
            } elseif ($kind === 'visits') {
                $lead->increment('visits');
                Communication::create([
                    'lead_id' => $lead->id,
                    'counselor_id' => Auth::id(),
                    'type' => 'call',
                    'direction' => 'inbound',
                    'status' => 'sent',
                    'sent_at' => now(),
                    'content' => 'Office visit',
                ]);
            }
            ActivityLog::record('lead.activity_logged', "Logged {$kind} for lead \"{$lead->name}\"");
            return response()->json(['saved' => true]);
        }

        // Handle toggling highly_interested
        if ($request->has('highly_interested')) {
            $lead->update(['highly_interested' => !$lead->highly_interested]);
            ActivityLog::record('lead.interest_toggled', "Toggled highly interested for lead \"{$lead->name}\"");
            return response()->json(['saved' => true]);
        }

        // Handle toggling contract_signed
        if ($request->has('toggle_contract')) {
            $lead->update([
                'contract_signed' => !$lead->contract_signed,
                'contract_date' => !$lead->contract_signed ? now()->format('Y-m-d') : null,
            ]);
            ActivityLog::record('lead.contract_toggled', "Toggled contract signed for lead \"{$lead->name}\"");
            return response()->json(['saved' => true]);
        }

        // Handle toggling payment_done
        if ($request->has('toggle_payment')) {
            $lead->update([
                'payment_done' => !$lead->payment_done,
                'payment_date' => !$lead->payment_done ? now()->format('Y-m-d') : null,
            ]);
            ActivityLog::record('lead.payment_toggled', "Toggled payment done for lead \"{$lead->name}\"");
            return response()->json(['saved' => true]);
        }

        $data = $request->validate([
            'contact' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'desired_program' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'last_qualification' => ['nullable', 'string', 'max:255'],
            'cgpa' => ['nullable', 'string', 'max:20'],
            'age' => ['nullable', 'integer', 'min:1', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'suggestion' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
            'currency' => ['nullable', 'string', 'max:255'],
            'english_test' => ['nullable', 'string', 'max:255'],
            'english_score' => ['nullable', 'string', 'max:255'],
        ]);

        $lead->update($data);

        ActivityLog::record('lead.profile_updated', "Updated student profile for lead \"{$lead->name}\"");

        if ($request->wantsJson()) {
            return response()->json(['saved' => true]);
        }

        return redirect()->route('leads.index')->with('status', 'Profile updated.');
    }

    public function cycleStatus(Lead $lead)
    {
        $this->authorizeLead($lead);

        $lead->update(['status' => $lead->nextStatus()]);

        return response()->json(['status' => $lead->status]);
    }

    public function moveToFollowUp(Lead $lead)
    {
        $this->authorizeLead($lead);

        // Move to follow-ups stage
        $lead->update([
            'funnel_stage' => 'Follow-up',
            'highly_interested' => true,
        ]);

        ActivityLog::record('lead.moved_to_followup', "Moved lead \"{$lead->name}\" to follow-ups");

        return response()->json([
            'funnel_stage' => $lead->funnel_stage,
        ]);
    }

    public function convert(Lead $lead)
    {
        $this->authorizeLead($lead);

        // Create a student record from the lead data
        $student = Student::create([
            'counselor_id' => $lead->counselor_id,
            'name' => $lead->name,
            'contact' => $lead->contact,
            'age' => $lead->age,
            'city' => $lead->city,
            'desired_program' => $lead->desired_program,
            'country' => $lead->country,
            'budget' => $lead->budget,
            'last_qualification' => $lead->last_qualification,
            'cgpa' => $lead->cgpa,
            'english_test' => $lead->english_test,
            'english_score' => $lead->english_score,
            'remarks' => $lead->remarks,
        ]);

        // Mark lead as converted/registered
        $lead->update([
            'status' => 'Registered',
            'funnel_stage' => 'Converted',
        ]);

        ActivityLog::record('lead.converted', "Converted lead \"{$lead->name}\" to registered student (ID: {$student->id})");

        return response()->json([
            'status' => $lead->status,
            'student_id' => $student->id,
        ]);
    }

    public function sendEmail(Request $request, Lead $lead)
    {
        $this->authorizeLead($lead);

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $to = $lead->email ?: (filter_var($lead->contact, FILTER_VALIDATE_EMAIL) ? $lead->contact : null);

        if (! $to) {
            return response()->json(['error' => 'This lead has no valid email address on file.'], 422);
        }

        $body = str_replace('{name}', $lead->name, $data['body']);

        try {
            Mail::to($to)->send(new LeadEmail($lead, Auth::user(), $data['subject'], $body));
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to send: '.$e->getMessage()], 500);
        }

        Communication::create([
            'lead_id' => $lead->id,
            'counselor_id' => Auth::id(),
            'type' => 'email',
            'subject' => $data['subject'],
            'direction' => 'outbound',
            'content' => $body,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        ActivityLog::record('lead.email_sent', "Emailed lead \"{$lead->name}\": {$data['subject']}");

        return response()->json(['success' => true]);
    }

    public function destroy(Lead $lead)
    {
        $this->authorizeLead($lead);

        $lead->delete();

        ActivityLog::record('lead.deleted', "Deleted lead \"{$lead->name}\"");

        return response()->json(['success' => true]);
    }

    public function storeReminder(Request $request, Lead $lead)
    {
        $this->authorizeLead($lead);

        $request->validate([
            'reminder_date' => ['required', 'date', 'after:now'],
            'title' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $reminder = LeadReminder::create([
            'lead_id' => $lead->id,
            'counselor_id' => Auth::id(),
            'reminder_date' => $request->reminder_date,
            'title' => $request->title,
            'notes' => $request->notes,
        ]);

        ActivityLog::record('lead.reminder_created', "Created reminder \"{$request->title}\" for lead \"{$lead->name}\"");

        return response()->json(['success' => true, 'reminder' => $reminder]);
    }

    public function completeReminder(LeadReminder $reminder)
    {
        if ($reminder->counselor_id !== Auth::id()) {
            abort(403);
        }

        $reminder->update(['status' => 'completed']);

        ActivityLog::record('lead.reminder_completed', "Completed reminder \"{$reminder->title}\"");

        return response()->json(['success' => true]);
    }

    public function snoozeReminder(Request $request, LeadReminder $reminder)
    {
        if ($reminder->counselor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'snooze_until' => ['required', 'date', 'after:now'],
        ]);

        $reminder->update([
            'status' => 'snoozed',
            'snoozed_until' => $request->snooze_until,
        ]);

        ActivityLog::record('lead.reminder_snoozed', "Snoozed reminder \"{$reminder->title}\" until {$request->snooze_until}");

        return response()->json(['success' => true]);
    }

    public function destroyReminder(LeadReminder $reminder)
    {
        if ($reminder->counselor_id !== Auth::id()) {
            abort(403);
        }

        $reminder->delete();

        ActivityLog::record('lead.reminder_deleted', "Deleted reminder \"{$reminder->title}\"");

        return response()->json(['success' => true]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $importedCount = 0;
        $createdCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $row = array_combine($header, array_pad($row, count($header), null));
            $studentName = $row['Student Name'] ?? ($row[array_key_first($row)] ?? '—');

            // Try to find existing lead by name
            $lead = Lead::where('counselor_id', Auth::id())
                        ->where('name', $studentName)
                        ->first();

            if ($lead) {
                // Update existing lead with any additional data
                if (isset($row['Contact'])) {
                    $lead->contact = $row['Contact'];
                }
                if (isset($row['Source'])) {
                    $lead->source = $row['Source'];
                }
                if (isset($row['Status'])) {
                    $lead->status = $row['Status'];
                }
                $lead->save();
                $importedCount++;
            } else {
                // Create new lead
                Lead::create([
                    'counselor_id' => Auth::id(),
                    'name' => $studentName,
                    'contact' => $row['Contact'] ?? null,
                    'source' => $row['Source'] ?? 'Other',
                    'status' => $row['Status'] ?? 'New',
                    'funnel_stage' => 'Lead',
                ]);
                $createdCount++;
            }
        }
        fclose($handle);

        return redirect()->route('leads.index')->with('status', "Imported {$importedCount} existing leads, created {$createdCount} new leads.");
    }

    public function template()
    {
        $csv = "Student Name,Contact,Source,Status\nJohn Doe,+1234567890,Website,New\nJane Smith,jane@example.com,Referral,Call\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leads_template.csv"',
        ]);
    }

    private function authorizeLead(Lead $lead): void
    {
        if ($lead->counselor_id !== Auth::id()) {
            abort(403);
        }
    }
}
