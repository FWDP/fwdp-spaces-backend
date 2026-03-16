<?php

namespace App\Core\Payments\Http\Controllers;

use App\Core\Payments\Models\Payment;
use App\Core\Subscriptions\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function checkout(
        Request $request,
        SubscriptionPlan $subscriptionPlan,
        Payment $payment
    )
    {
        $latestSubscription = $request->user()
            ->subscriptions()
            ->firstOrCreate(
                ['user_id' => $request->user()->id],
                [
                    'subscription_plan_id' => $subscriptionPlan->newQuery()->findOrFail($request->validate([
                        'subscription_plan_id' => 'required|exists:subscription_plans,id',
                    ])['subscription_plan_id'])->value('id'),
                    'status' => 'trial',
                    'start_date' => now(),
                    'end_date' => now()->addDays($subscriptionPlan->newQuery()->value('duration_days')),
                ]
            );

        $latestSubscription->update([
            'subscription_plan_id' => $subscriptionPlan->newQuery()->findOrFail($request->validate([
                'subscription_plan_id' => 'required|exists:subscription_plans,id',
            ])['subscription_plan_id'])->value('id'),
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays($subscriptionPlan->newQuery()->value('duration_days')),
        ]);

        return response()->json([
            'status' => 'Payment successful',
            'payment' => $payment->newQuery()
                ->create([
                    'user_id'   => $request->user()->id,
                    'subscription_id' => $latestSubscription->newQuery()->value('id'),
                    'amount'    => $subscriptionPlan->newQuery()->findOrFail($request->validate([
                        'subscription_plan_id' => 'required|exists:subscription_plans,id',
                    ])['subscription_plan_id'])->toArray()['price'],
                    'status'    => 'success',
                    'provider' => 'test',
                    'provider_reference' => 'TEST-'.uniqid(),
                ]),
            'subscription' => $latestSubscription->fresh('subscription_plan'),
        ], 201);
    }
}
