<?php

namespace App\Core\Subscriptions\Enums;

enum SubscriptionStatus: string
{
    case TRIAL = 'trial';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';
}
