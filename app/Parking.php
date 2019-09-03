<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Parking extends Model
{
    protected $appends = ['iscurrentuseradmin', 'isparkinguser'];

    public function getIsCurrentUserAdminAttribute()
    {
        $userParking = $this->usersParkings()
            ->where('parkingid', '=', $this->id)
            ->where('userid', '=', Auth::user()->id)
            ->first();

        return $userParking && $userParking->isadmin;
    }

    public function getIsParkingUserAttribute()
    {
        return (bool)$this->getUserParking();
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, UsersParking::class, 'parkingid', 'id');
    }

    public function usersParkings()
    {
        return $this->hasMany(UsersParking::class, 'parkingid', 'id');
    }

    public function spots()
    {
        return $this->hasMany(Spot::class, 'parkingid', 'id');
    }

    private function getUserParking()
    {
        return $this->usersParkings()
            ->where('parkingid', '=', $this->id)
            ->where('userid', '=', Auth::user()->id)
            ->first();
    }
}
