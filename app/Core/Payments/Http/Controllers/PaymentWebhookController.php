<?php

namespace App\Core\Payments\Http\Controllers;

use App\Core\Payments\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        if (!$this->verifySignature($request)) {
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $event   = $request->input('data.attributes.type');
        $payload = $request->input('data.attributes.data.attributes', []);
        $id      = $request->input('data.attributes.data.id');

        match ($event) {
            'payment.paid'   => $this->handlePaymentPaid($id, $payload),
            'payment.failed' => $this->handlePaymentFailed($id, $payload),
            default          => Log::info("PayMongo unhandled event: {$event}"),
        };

        return response()->json(['message' => 'Webhook processed.']);
    }

    protected function handlePaymentPaid(string $id, array $attributes): void
    {
        // PayMongo payment ID is different from our provider_reference (PaymentIntent ID)
        // The payment_intent_id is inside the payment's attributes
        $intentId = $attributes['payment_intent_id'] ?? $id;

        $payment = Payment::where('provider_reference', $intentId)->first();

        if (!$payment) {
            Log::warning("PayMongo webhook: payment not found for intent {$intentId}");
            return;
        }

        $payment->update(['status' => 'success']);

        $payment->subscription?->update([
            'status'   => 'active',
            'end_date' => now()->addDays(30),
        ]);
    }

    protected function handlePaymentFailed(string $id, array $attributes): void
    {
        $intentId = $attributes['payment_intent_id'] ?? $id;

        $payment = Payment::where('provider_reference', $intentId)->first();

        if (!$payment) {
            Log::warning("PayMongo webhook: payment not found for intent {$intentId}");
            return;
        }

        $payment->update(['status' => 'failed']);
    }

    protected function verifySignature(Request $request): bool
    {
        $webhookSecret = config('payments.paymongo.webhook_secret');

        if (!$webhookSecret) return true; // skip in local/testing

        $signature = $request->header('Paymongo-Signature');

        if (!$signature) return false;

        // PayMongo signature format: t=timestamp,te=test_hash,li=live_hash
        $parts = collect(explode(',', $signature))
            ->mapWithKeys(function ($part) {
                [$key, $value] = explode('=', $part, 2);
                return [$key => $value];
            });

        $timestamp = $parts->get('t');
        $payload   = $timestamp . '.' . $request->getContent();
        $expected  = hash_hmac('sha256', $payload, $webhookSecret);

        // Use 'li' for live mode, 'te' for test mode
        $isLive    = str_starts_with(config('payments.paymongo.secret_key', ''), 'sk_live');
        $received  = $parts->get($isLive ? 'li' : 'te', '');

        return hash_equals($expected, $received);
    }
}
