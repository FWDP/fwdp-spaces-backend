<?php

namespace App\Core\Payments\Http\Controllers;

use App\Core\Payments\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $reference = $request->input('reference');

        $payment = Payment::where('provider_reference', $reference)->firstOrFail();

        $payment->update(['status' => 'success']);

        $subscription = $payment->subscription;

        $subscription->update(['status' => 'active']);

        return response()->json([
            'message' => 'Webhook processed.',
        ]);
    }
}
