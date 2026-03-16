<?php

namespace App\Core\Payments\Http\Controllers;

use App\Core\Payments\Models\Payment;
use App\Core\Payments\Services\PaymentService;
use App\Core\Subscriptions\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function checkout(
        Request $request
    )
    {
        $data = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($data['plan_id']);

        return response()->json([
            'payment' => $this->paymentService->checkout($request->user(), $plan),
        ], 201);
    }
}
