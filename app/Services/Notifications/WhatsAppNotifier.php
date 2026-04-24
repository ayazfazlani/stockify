<?php

namespace App\Services\Notifications;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotifier
{
    public function send(string $to, string $message): void
    {
        $webhookUrl = config('services.whatsapp.webhook_url');

        if (! $webhookUrl) {
            Log::info('WhatsApp alert skipped (no webhook configured)', ['to' => $to, 'message' => $message]);
            return;
        }

        Http::post($webhookUrl, [
            'to' => $to,
            'message' => $message,
        ]);
    }
}
