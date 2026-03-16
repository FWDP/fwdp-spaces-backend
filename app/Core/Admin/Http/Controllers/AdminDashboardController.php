<?php

namespace App\Core\Admin\Http\Controllers;

use App\Core\Payments\Models\Payment;
use App\Core\Subscriptions\Models\Subscription;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'users' => [
                'total' => User::count(),
            ],

            'subscriptions' => [
                'active' => Subscription::query()->where('status', 'active')->count(),
                'expired' => Subscription::query()->where('status', 'expired')->count(),
                'trial' => Subscription::query()->where('status', 'trial')->count(),
            ],

            'payments' => [
                'total' => Payment::query()->count(),
                'successful' => Payment::query()->where('status', 'success')->count(),
                'failed' => Payment::query()->where('status', 'failed')->count(),
            ],
        ]);
    }
}
