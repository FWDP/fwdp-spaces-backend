<?php

namespace App\Core\Payments\Services;

use App\Core\Payments\Contracts\PaymentGateway;
use App\Core\Payments\Models\Payment;
use App\Core\Subscriptions\Models\SubscriptionPlan;
use App\Models\User;

class PaymentService
{
    protected PaymentGateway $gateway;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function checkout(
        User $user,
        SubscriptionPlan $plan,
    ): Payment
    {
        $subscription = $user->subscriptions()
            ->latest()
            ->first();

        $paymentIntent = $this->gateway->createPayment([
            'amount' => $plan->price,
            'currency' => 'PHP',
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'amount' => $plan->price,
            'currency' => 'PHP',
            'status' => $paymentIntent['status'],
            'provider' => 'test',
            'provider_reference' => $paymentIntent['reference'],
        ]);

        if ($payment->isSuccessful()) $subscription->update([
            'plan_id' => $plan->id,
            'status' => 'active',
            'end_date' => now()->addDays(30)
        ]);

        return $payment;
    }
}