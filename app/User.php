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
     * Get the shops for the user
     */
    public function shops()
    {
        return $this->hasMany('App\Shop');
    }

    /**
     * Get the providers for the user
     */
    public function providers()
    {
        return $this->hasMany('App\UserProvider');
    }

}
