<?php

namespace App\Mail;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Contract $contract,
        public bool $forClient,
    ) {}

    public function build(): self
    {
        $subject = $this->forClient
            ? 'Your Student Service Agreement — The Guiders'
            : "Contract generated for {$this->contract->student->name}";

        return $this->subject($subject)
            ->view('emails.contract')
            ->with([
                'contract' => $this->contract,
                'forClient' => $this->forClient,
            ]);
    }
}
