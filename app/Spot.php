<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $appends = ['iscurrentuseradmin', 'isparkinguser'];

    public function getIsCurrentUserAdminAttribute()
    {
        return $this->parking->iscurrentuseradmin;
    }

    public function getIsParkingUserAttribute()
    {
        return $this->parking->isparkinguser;
    }

    public function occupier()
    {
        return $this->hasOne(User::class, 'id', 'occupiedby');
    }

    public function parking()
    {
        return $this->hasOne(Parking::class, 'id', 'parkingid');
    }
}
