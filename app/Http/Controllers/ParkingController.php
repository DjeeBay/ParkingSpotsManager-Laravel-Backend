<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParkingChangeUserRoleRequest;
use App\Http\Requests\ParkingUpdateRequest;
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

    public function update(ParkingUpdateRequest $request, $id)
    {
        $parking = Parking::find($id);
        if ($parking && $parking->iscurrentuseradmin) {
            $parking->name = $request->Name;
            $parking->address = $request->Address;
            $parking->latitude = $request->Latitude;
            $parking->longitude = $request->Longitude;
            $parking->save();

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

    public function changeUserRole(ParkingChangeUserRoleRequest $request, $parkingID)
    {
        $parking = Parking::find($parkingID);
        $userParking = UsersParking::find($request->Id);
        if ($parking && $userParking && $parking->iscurrentuseradmin && $request->UserId !== Auth::user()->id) {
            $userParking->isadmin = $request->IsAdmin;
            $userParking->save();

            return response()->json(UsersParking::with('user')->where('parkingid', '=', $parkingID)->get());
        }

        return response()->json('Bad Request', Response::HTTP_BAD_REQUEST);
    }

    public function removeUser($parkingID, $userID)
    {
        $userParking = UsersParking::where([
            ['parkingid', '=', $parkingID],
            ['userid', '=', $userID],
        ])
            ->first();
        $parking = Parking::find($parkingID);
        if ($userParking && $parking && $parking->iscurrentuseradmin && intval($userID) !== Auth::user()->id) {
            $userParking->delete();

            return response()->json(UsersParking::with('user')->where('parkingid', '=', $parkingID)->get());
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
