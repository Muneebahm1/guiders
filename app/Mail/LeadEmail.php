<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Lead $lead,
        public User $sender,
        public string $emailSubject,
        public string $bodyText,
    ) {}

    public function build(): self
    {
        return $this->subject($this->emailSubject)
            ->view('emails.lead')
            ->with([
                'bodyText' => $this->bodyText,
                'senderName' => $this->sender->name,
            ]);
    }
}
