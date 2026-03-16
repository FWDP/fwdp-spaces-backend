<?php

namespace App\Models;

use App\Core\Membership\Contracts\Authenticable;
use App\Core\Membership\Contracts\HasProfile;
use App\Core\Membership\Contracts\HasRole;
use App\Core\Profile\Models\UserProfile;
use App\Core\Subscriptions\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements Authenticable, HasRole, HasProfile
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'user_id',
        'subscription_plan_id',
        'status',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne|User
    {
        return $this->hasOne(UserProfile::class);
    }

    public function getRole(): string
    {
        // TODO: Implement getRole() method.
        return $this->role;
    }

    public function hasRole(string $role): bool
    {
        // TODO: Implement hasRole() method.
        return $this->role === $role;
    }
}
