<?php

namespace App\Http\Controllers;

use App\Parking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParkingController extends Controller
{
    public function getUserParkings()
    {
        $parkings = Parking::with(['users', 'spots'])
            ->whereHas('users', function (Builder $q) {
                $q->where('users_parkings.userid', '=', Auth::user()->id);
            })
            ->get();

        return response()->json($parkings);
    }
}
