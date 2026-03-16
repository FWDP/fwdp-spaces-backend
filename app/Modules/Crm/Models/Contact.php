<?php

namespace App\Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $table = 'crm_contacts';

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'type',
        'assigned_to', 'notes', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'contact_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'contact_id');
    }
}
