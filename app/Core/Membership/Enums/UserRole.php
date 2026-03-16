<?php

namespace App\Core\Membership\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';
    case MSME_USER = 'msme_user';
    case INSTRUCTOR = 'instructor';
}
