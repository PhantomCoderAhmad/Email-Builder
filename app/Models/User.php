<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'uuid',
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'auth_provider',
        'status',
        'remember_token',
        'referral',
        'refer_by',
        'forgotToken',
        'fcm_token',
        'type',
        'auth_code',
        'is_2way_auth',
        'google2fa_secret',
        'is_anonymous',
        'merged_in_user',
        'is_2fa_enabled',
        'last_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'auth_code',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_active' => 'datetime',
        'is_2fa_enabled' => 'boolean',
        'is_anonymous' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get the user's profile.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    /**
     * Create a user with a related profile.
     */
    public function hasProfile($count = 1)
    {
        return $this->hasOne(UserProfile::class)->count($count);
    }
}
