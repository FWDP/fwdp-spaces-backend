<?php

namespace App\Http\Controllers\Admin;

use App\Core\Subscriptions\Models\Subscription;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function store(Request $request, $userId)
    {
        $subscription = Subscription::where('user_id',$userId)
            ->latest()->firstOrFail();

        return Payment::updateOrCreate([
            'user_id'=>$userId,
            'subscription_id'=>$subscription->id,
            'provider'=>$request->provider ?? 'manual',
            'amount'=>$request->amount,
            'reference'=>$request->reference,
        ]);
    }

    public function confirm($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->confirm();

        $plan = $payment->subscription->plan;
        $payment->subscription->activate($plan->duration_days);

        return response()->json(['message'=>'Activated']);
    }

}
