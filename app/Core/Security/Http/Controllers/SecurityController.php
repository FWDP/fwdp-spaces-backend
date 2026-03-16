<?php

namespace App\Core\Security\Http\Controllers;

use App\Core\Security\Models\BlockedIp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function blockedIps()
    {
        return BlockedIp::all();
    }

    public function blockIp(Request $request)
    {
        $data = $request->validate([
            'ip_address' => 'required',
            'reason' => 'nullable',
        ]);

        BlockedIp::query()->create([
            'ip_address' => $data['ip_address'],
            'reason' => $data['reason'],
            'blocked_at' => now(),
        ]);

        return response()->json([
            'message' => 'IP Blocked',
        ]);
    }

    public function unblockIp(BlockedIp $blockedIp)
    {
        $blockedIp->delete();

        return response()->json([
            'message' => 'IP unblocked',
        ]);
    }
}
