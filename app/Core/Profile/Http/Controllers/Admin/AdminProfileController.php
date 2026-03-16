<?php

namespace App\Core\Profile\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
    public function index(Request $request, User $user)
    {
        $query = User::query()->with('profile');

        // --- Search by user fields ---
        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($query) use ($q) {
                $query->where('name', 'LIKE', "%{$q}%")
                    ->orWhere('email', 'LIKE', "%{$q}%");
            });
        }

        return $user->paginate(20);
    }

    public function show($id)
    {
        $user = User::with('profile')->findOrFail($id);

        return $user->profile;
    }
}
