<?php

namespace App\Core\Subscriptions\Http\Controllers;

use App\Core\Subscriptions\Models\Subscription;
use App\Core\Subscriptions\Models\SubscriptionPlan;
use App\Core\Subscriptions\Services\SubscriptionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    function current(Request $request)
    {
        return response()->json([
            'subscription' => $this->subscriptionService->getCurrentSubscription($request->user()),
        ]);
    }

    public function plans(SubscriptionPlan $subscriptionPlan)
    {
        return SubscriptionPlan::where('is_active', 1)->get();
    }
}
