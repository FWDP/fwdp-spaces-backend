<?php

namespace App\Core\Features\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    protected $fillable = [
      'plan_id',
      'feature_id',
    ];
}
