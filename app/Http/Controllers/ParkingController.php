<?php

namespace App\Http\Controllers;

use App\Parking;
use App\UsersParking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ParkingController extends Controller
{
    public function get(Request $request, $id)
    {
        $parking = Parking::with(['spots.occupier', 'userparkings.user'])->find($id);
        if ($parking) {
            return response()->json($parking);
        }

        return response('Bad Request', Response::HTTP_BAD_REQUEST);
    }

    public function getUserParkings()
    {
        $parkings = $this->getParkingsOfCurrentUser();

        return response()->json($parkings);
    }

    public function leave(Request $request, $parkingID)
    {
        $parking = Parking::find($parkingID);
        if ($parking && $parking->isparkinguser) {
            $userParking = UsersParking::where([
                ['parkingid', '=', $parking->id],
                ['userid', '=', Auth::user()->id]
            ])
                ->first();
            if ($userParking) $userParking->delete();

            return response()->json($this->getParkingsOfCurrentUser());
        }

        return response()->json('Bad Request', Response::HTTP_BAD_REQUEST);
    }

    private function getParkingsOfCurrentUser()
    {
        return Parking::with(['users', 'spots'])
            ->whereHas('users', function (Builder $q) {
                $q->where('users_parkings.userid', '=', Auth::user()->id);
            })
            ->get();
    }
}
