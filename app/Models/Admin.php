<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends User
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable =  [
        'name',
        'password',
        'email',
        'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
     * Get all of the parts for the Admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parts(): HasMany
    {
        return $this->hasMany(Parts::class, 'admin_id', 'id');
    }
}
