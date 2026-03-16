<?php

namespace App\Modules\Learning\Services;

use App\Modules\Learning\Models\Certificate;

class CertificateService
{
    public function generate($userId, $courseId)
    {
        return Certificate::query()->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'certificate_url' => "/certificates/$userId-$courseId.pdf",
        ]);
    }
}
