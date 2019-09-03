<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $hidden = ['password'];

    public function parkings()
    {
        return $this->hasManyThrough(Parking::class, UsersParking::class, 'userid', 'id');
    }
}
