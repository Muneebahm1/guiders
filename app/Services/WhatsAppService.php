<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function isConfigured(): bool
    {
        $provider = config('services.whatsapp.provider');

        if ($provider === 'meta') {
            return filled(config('services.whatsapp.meta.phone_number_id'))
                && filled(config('services.whatsapp.meta.access_token'));
        }

        if ($provider === 'twilio') {
            return filled(config('services.whatsapp.twilio.account_sid'))
                && filled(config('services.whatsapp.twilio.auth_token'))
                && filled(config('services.whatsapp.twilio.from_number'));
        }

        return false;
    }

    public function send(string $to, string $message): bool
    {
        if (! $this->isConfigured()) {
            Log::info("WhatsApp not configured — would have sent to {$to}: {$message}");

            return false;
        }

        return match (config('services.whatsapp.provider')) {
            'twilio' => $this->sendViaTwilio($to, $message),
            default => $this->sendViaMeta($to, $message),
        };
    }

    protected function sendViaMeta(string $to, string $message): bool
    {
        $phoneNumberId = config('services.whatsapp.meta.phone_number_id');
        $token = config('services.whatsapp.meta.access_token');

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/v20.0/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => ['body' => $message],
            ]);

        if ($response->failed()) {
            Log::warning("WhatsApp (Meta) send to {$to} failed: {$response->body()}");

            return false;
        }

        return true;
    }

    protected function sendViaTwilio(string $to, string $message): bool
    {
        $sid = config('services.whatsapp.twilio.account_sid');
        $token = config('services.whatsapp.twilio.auth_token');
        $from = config('services.whatsapp.twilio.from_number');

        $response = Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'From' => $from,
                'To' => 'whatsapp:'.$to,
                'Body' => $message,
            ]);

        if ($response->failed()) {
            Log::warning("WhatsApp (Twilio) send to {$to} failed: {$response->body()}");

            return false;
        }

        return true;
    }
}
