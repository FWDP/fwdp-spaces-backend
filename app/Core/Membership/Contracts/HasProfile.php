<?php

namespace App\Core\Membership\Contracts;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface HasProfile
{
    public function profile(): HasOne;
}
