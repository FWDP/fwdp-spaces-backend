<?php

namespace App\Core\Audit\Services;

use App\Core\Audit\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public function log(
        string $event,
        ?int $userId = null,
        ?string $entityType = null,
        ?string $entityId = null,
        array $metadata = []
    ): AuditLog
    {
        return AuditLog::query()->create([
            'user_id' => $userId,
            'event' => $event,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'metadata' => $metadata,
            'ip_address'=> Request::ip(),
            'user_agent'=> Request::userAgent(),
            'created_at' => now(),
        ]);
    }
}