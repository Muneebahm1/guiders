<?php

namespace App\Http\Controllers;

use App\Mail\LeadDocumentUploadedMail;
use App\Models\Lead;
use App\Models\LeadDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class LeadDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:counselor');
    }

    public function store(Request $request, Lead $lead)
    {
        $this->authorizeLead($lead);

        $request->validate([
            'document_type' => ['required', 'string', 'in:' . implode(',', LeadDocument::DOCUMENT_TYPES)],
            'file' => ['required', 'file', 'max:10240'], // Max 10MB
            'notes' => ['nullable', 'string'],
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('lead_documents', $fileName, 'public');

        $document = LeadDocument::create([
            'lead_id' => $lead->id,
            'counselor_id' => Auth::id(),
            'document_type' => $request->document_type,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'notes' => $request->notes,
        ]);

        $this->sendDocumentEmails($document, $lead);

        return response()->json([
            'success' => true,
            'document' => $document,
            'url' => Storage::url($filePath),
        ]);
    }

    private function sendDocumentEmails(LeadDocument $document, Lead $lead): void
    {
        $document->setRelation('counselor', Auth::user());
        $document->setRelation('lead', $lead);

        if ($lead->email) {
            try {
                Mail::to($lead->email)->send(new LeadDocumentUploadedMail($document, forLead: true));
            } catch (\Throwable $e) {
                Log::warning("Failed to send document email to lead: {$e->getMessage()}");
            }
        }

        // Counselor + admins get the identical internal notice, sent as one
        // message (to + cc) so it's a single SMTP transaction rather than
        // several back-to-back ones (Mailtrap's sandbox plan rate-limits
        // rapid consecutive sends and silently drops the later ones).
        $adminEmails = User::where('role', 'admin')->pluck('email')->filter()
            ->reject(fn ($email) => $email === Auth::user()->email)
            ->all();

        if (Auth::user()->email) {
            try {
                Mail::to(Auth::user()->email)
                    ->cc($adminEmails)
                    ->send(new LeadDocumentUploadedMail($document, forLead: false));
            } catch (\Throwable $e) {
                Log::warning("Failed to send document email to counselor/admins: {$e->getMessage()}");
            }
        } elseif ($adminEmails) {
            try {
                Mail::to($adminEmails)->send(new LeadDocumentUploadedMail($document, forLead: false));
            } catch (\Throwable $e) {
                Log::warning("Failed to send document email to admins: {$e->getMessage()}");
            }
        }
    }

    public function destroy(LeadDocument $document)
    {
        $this->authorizeDocument($document);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return response()->json(['success' => true]);
    }

    private function authorizeLead(Lead $lead): void
    {
        if ($lead->counselor_id !== Auth::id()) {
            abort(403);
        }
    }

    private function authorizeDocument(LeadDocument $document): void
    {
        if ($document->counselor_id !== Auth::id()) {
            abort(403);
        }
    }
}
