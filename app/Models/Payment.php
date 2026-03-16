<?php

namespace App\Models;

use App\Core\Subscriptions\Models\Subscription;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id','subscription_id','provider',
        'amount','currency','status','reference','paid_at'
    ];

    protected $casts = [
        'paid_at'=>'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function confirm()
    {
        $this->update([
            'status'=>'confirmed',
            'paid_at'=>now(),
        ]);
    }
}
