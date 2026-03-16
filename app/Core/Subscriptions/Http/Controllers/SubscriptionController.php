<?php

namespace App\Core\Subscriptions\Http\Controllers;

use App\Core\Subscriptions\Models\Subscription;
use App\Core\Subscriptions\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    function index(Request $request,  Subscription $subscription)
    {
        return response()->json([
            'subscription' => $subscription
                ->with('plan')
                ->where('user_id', $request->user()->id)
                ->latest()
                ->first(),
        ]);
    }

    public function plans(SubscriptionPlan $subscriptionPlan)
    {
        return response()->json([
            'plans' => $subscriptionPlan->newQuery()->get()
        ]);
    }
}
