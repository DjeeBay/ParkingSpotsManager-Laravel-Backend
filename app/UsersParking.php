<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersParking extends Model
{
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userid');
    }
}
