<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the stores for the user
     */
    public function stores()
    {
        return $this->belongsToMany(
            'App\Store', 'store_users', 'store_id', 'user_id'
        );
    }

    /**
     * Get the providers for the user
     */
    public function providers()
    {
        return $this->hasMany('App\UserProvider');
    }

}
