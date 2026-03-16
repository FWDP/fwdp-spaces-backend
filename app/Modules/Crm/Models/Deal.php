<?php

namespace App\Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deal extends Model
{
    protected $table = 'crm_deals';

    protected $fillable = [
        'contact_id', 'title', 'value', 'stage',
        'close_date', 'assigned_to', 'notes',
    ];

    protected $casts = ['value' => 'decimal:2', 'close_date' => 'date'];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'deal_id');
    }
}
