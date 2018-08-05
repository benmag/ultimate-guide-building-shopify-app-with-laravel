<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'domain'
    ];

    /**
     * Get all of the users that belong to the shop.
     */
    public function users()
    {
        return $this->belongsToMany(
            'App\User', 'shop_users', 'shop_id', 'user_id'
        );
    }

}
