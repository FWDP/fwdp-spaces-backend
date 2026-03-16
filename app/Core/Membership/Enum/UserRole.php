<?php

namespace App\Core\Membership\Enum;

enum UserRole: string
{
    case ADMIN = 'ADMIN';
    case MODERATOR = 'MODERATOR';

    case MSME_USER = 'MSME_USER';
}
