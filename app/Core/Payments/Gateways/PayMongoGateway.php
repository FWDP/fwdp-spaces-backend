<?php

namespace App\Core\Payments\Gateways;

use App\Core\Payments\Contracts\PaymentGateway;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayMongoGateway implements PaymentGateway
{
    protected string $baseUrl = 'https://api.paymongo.com/v1';
    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('payments.paymongo.secret_key');
    }

    public function createPayment(array $data): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->post("{$this->baseUrl}/payment_intents", [
                'data' => [
                    'attributes' => [
                        'amount'               => (int) round($data['amount'] * 100), // centavos
                        'currency'             => $data['currency'] ?? 'PHP',
                        'payment_method_allowed' => ['gcash', 'paymaya', 'card', 'dob', 'brankas', 'grab_pay', 'shopee_pay'],
                        'description'          => $data['description'] ?? 'Subscription Payment',
                        'capture_type'         => 'automatic',
                    ],
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('PayMongo createPayment failed: ' . $response->body());
        }

        $intent = $response->json('data');

        return [
            'reference' => $intent['id'],
            'status'    => $this->mapStatus($intent['attributes']['status']),
            'client_key' => $intent['attributes']['client_key'],
            'raw'       => $intent,
        ];
    }

    public function verifyPayment(array $reference): array
    {
        $id = $reference['reference'] ?? $reference[0];

        $response = Http::withBasicAuth($this->secretKey, '')
            ->get("{$this->baseUrl}/payment_intents/{$id}");

        if ($response->failed()) {
            throw new RuntimeException('PayMongo verifyPayment failed: ' . $response->body());
        }

        $intent = $response->json('data');

        return [
            'reference' => $intent['id'],
            'status'    => $this->mapStatus($intent['attributes']['status']),
            'raw'       => $intent,
        ];
    }

    protected function mapStatus(string $paymongoStatus): string
    {
        return match ($paymongoStatus) {
            'succeeded'        => 'success',
            'awaiting_payment_method',
            'awaiting_next_action' => 'pending',
            'processing'       => 'processing',
            default            => 'failed',
        };
    }
}
