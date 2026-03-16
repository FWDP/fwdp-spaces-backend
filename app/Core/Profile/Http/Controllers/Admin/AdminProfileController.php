<?php

namespace App\Core\Profile\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
    public function index(Request $request, User $user)
    {
        // --- Search by user fields ---
        if ($request->filled('q')) {
            $q = $request->q;

            $user->with('profile')->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        return $user->paginate(20);
    }

    public function show(Request $request, $id)
    {
        return $request->user()->findOrFail($id)->profile;
    }
}
