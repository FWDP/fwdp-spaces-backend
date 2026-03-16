<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    protected $table = 'units_of_measure';

    protected $fillable = ['name', 'abbreviation'];
}
