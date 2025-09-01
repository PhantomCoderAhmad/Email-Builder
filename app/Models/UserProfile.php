<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scanned_count',
        'scan_points',
        'total_reward_points',
        'allow_notifications',
        'latitude',
        'longitude',
        'timeZoneName',
        'city',
        'country',
        'address',
        'contact',
        'device',
        'slug',
        'about_me',
        'app_opening',
        'app_version',
        'start_earning',
        'watch_earning',
    ];

    protected $casts = [
        'allow_notifications' => 'boolean',
        'start_earning' => 'boolean',
        'scanned_count' => 'integer',
        'scan_points' => 'integer',
        'total_reward_points' => 'integer',
        'watch_earning' => 'integer',
        'app_opening' => 'integer',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
