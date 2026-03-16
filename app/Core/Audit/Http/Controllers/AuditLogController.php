<?php

namespace App\Core\Audit\Http\Controllers;

use App\Core\Audit\Models\AuditLog;
use App\Http\Controllers\Controller;

class AuditLogController extends Controller
{
    public function index()
    {
        return AuditLog::query()
            ->latest()
            ->paginate(50);
    }

    public function show(AuditLog $auditLog)
    {
        return $auditLog;
    }
}
