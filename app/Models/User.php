<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'name',
        'email',
        'password',
        'facebook_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'facebook_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the followers
     */
    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class, 'login_id');
    }

    /**
     * Get the subscribers
     */
    public function subscribers(): HasMany
    {
        return $this->hasMany(Subscriber::class, 'login_id');
    }

    /**
     * Get the donations
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'login_id');
    }

    /**
     * Get the merchant sales
     */
    public function merchSales(): HasMany
    {
        return $this->hasMany(MerchSale::class, 'login_id');
    }
}
