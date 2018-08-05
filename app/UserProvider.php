<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProvider extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'provider', 'provider_user_id', 'provider_token',
    ];

}
