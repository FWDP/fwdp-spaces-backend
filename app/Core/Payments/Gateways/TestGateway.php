<?php

namespace App\Core\Payments\Gateways;

use App\Core\Payments\Contracts\PaymentGateway;

class TestGateway implements PaymentGateway
{

    public function createPayment(array $data): array
    {
        return [
            'reference' => 'TEST-' . uniqid(),
            'status' => 'success',
        ];
    }

    public function verifyPayment(array $reference): array
    {
        return [
            'reference' => true,
        ];
    }
}