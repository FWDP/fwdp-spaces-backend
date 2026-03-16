<?php

namespace App\Core\Webhooks\Services;

use App\Core\Webhooks\Models\Webhook;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class WebhookService
{
    public function dispatch(string $event, array $payload = []): void
    {
        $webhooks = Webhook::query()
            ->where('event', $event)
            ->where('active', true)
            ->get();

        foreach ($webhooks as $webhook) {
            $this->send($webhook, $payload);
        }
    }

    /**
     * @throws ConnectionException
     */
    public function send(Webhook $webhook, array $payload): void
    {
        $headers = [];

        if ($webhook->secret) {
            $headers['X-Signature'] = hash_hmac(
                'sha256',
                json_encode($payload),
                $webhook->secret
            );
        }

        Http::withHeaders($headers)
            ->post($webhook->url, $payload);
    }
}
