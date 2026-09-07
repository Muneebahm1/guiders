<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // WhatsApp — App\Services\WhatsAppService sends through whichever provider
    // is set here. The UI button stays disabled until one is fully configured.
    'whatsapp' => [
        'provider' => env('WHATSAPP_PROVIDER', 'meta'), // 'meta' or 'twilio'

        'meta' => [
            'phone_number_id' => env('WHATSAPP_META_PHONE_NUMBER_ID'),
            'access_token' => env('WHATSAPP_META_ACCESS_TOKEN'),
            'business_account_id' => env('WHATSAPP_META_BUSINESS_ACCOUNT_ID'),
            'webhook_verify_token' => env('WHATSAPP_META_WEBHOOK_VERIFY_TOKEN'),
        ],

        'twilio' => [
            'account_sid' => env('WHATSAPP_TWILIO_ACCOUNT_SID'),
            'auth_token' => env('WHATSAPP_TWILIO_AUTH_TOKEN'),
            'from_number' => env('WHATSAPP_TWILIO_FROM_NUMBER'), // e.g. whatsapp:+14155238886
        ],
    ],

];
