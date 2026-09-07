<?php

namespace App\Mail;

use App\Models\LeadDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadDocumentUploadedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LeadDocument $document,
        public bool $forLead,
    ) {}

    public function build(): self
    {
        $lead = $this->document->lead;

        $subject = $this->forLead
            ? "We received your document — {$this->document->document_type}"
            : "Document uploaded for {$lead->name}: {$this->document->document_type}";

        return $this->subject($subject)
            ->view('emails.lead-document')
            ->with([
                'document' => $this->document,
                'lead' => $lead,
                'forLead' => $this->forLead,
            ]);
    }
}
