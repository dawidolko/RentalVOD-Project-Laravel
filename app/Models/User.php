<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'address',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    

    public function opinions()
    {
        return $this->hasMany(Opinion::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin() : bool
    {
        return $this->role && $this->role->name == 'admin';
    }

    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoint::class);
    }

    public function referralCode()
    {
        return $this->hasOne(ReferralCode::class);
    }
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'accepted');
    }

    public function friendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'pending');
    }

    public function sentFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'pending');
    }

    public function receivedFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'pending');
    }

    public function movieRecommendations()
    {
        return $this->hasMany(Recommendation::class, 'friend_id');
    }
}

