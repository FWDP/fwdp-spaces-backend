<?php

namespace App\Core\Support\Modules;

use Illuminate\Database\Eloquent\Model;

class ModuleRecord extends Model
{
    protected $table = 'modules';

    protected $fillable = [
        'name',
        'version',
        'enabled',
        'installed_at',
    ];

    protected $casts = [
        'enabled'      => 'boolean',
        'installed_at' => 'datetime',
    ];
}
