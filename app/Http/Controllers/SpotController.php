<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpotChangeStatusRequest;
use App\Http\Requests\SpotCreateRequest;
use App\Http\Requests\SpotUpdateRequest;
use App\Parking;
use App\Spot;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SpotController extends Controller
{
    public function getParkingSpots(Request $request, $parkingID)
    {
        return response()->json($this->getSpotsOfAGivenParking($parkingID));
    }

    public function get(Request $request, $id)
    {
        if ($spot = Spot::find($id)) {
            return response()->json($spot);
        }

        return response('Bad Request', Response::HTTP_BAD_REQUEST);
    }

    public function update(SpotUpdateRequest $request, $id)
    {
        $spot = Spot::find($id);
        if ($spot && $spot->iscurrentuseradmin) {
            $spot->name = $request->Name;
            $spot->isoccupiedbydefault = $request->IsOccupiedByDefault;
            if (!$request->IsOccupiedByDefault) {
                $spot->occupiedbydefaultby = null;
            }
            $spot->save();

            return response()->json($spot);
        }

        return response('Bad Request', Response::HTTP_BAD_REQUEST);
    }

    public function store(SpotCreateRequest $request)
    {
        $parking = Parking::find($request->ParkingId);
        if ($parking && $parking->iscurrentuseradmin) {
            $spot = new Spot();
            $spot->name = $request->Name;
            $spot->parkingid = $request->ParkingId;
            $spot->isoccupiedbydefault = false;
            $spot->save();

            return response()->json($spot);
        }

        return response('Bad Request', Response::HTTP_BAD_REQUEST);
    }

    public function delete(Request $request, $id)
    {
        $spot = Spot::find($id);
        if ($spot && $spot->iscurrentuseradmin) {
            $spot->delete();

            return response('Spot deleted.');
        }

        return response('Bad Request', Response::HTTP_BAD_REQUEST);
    }

    public function getDefaultOccupier(Request $request, $id)
    {
        $spot = Spot::find($id);
        if ($spot && $spot->isoccupiedbydefault && $spot->occupiedbydefaultby && $user = User::find($spot->occupiedbydefaultby)) {
            return response()->json($user);
        }

        return response('Bad Request', Response::HTTP_BAD_REQUEST);
    }

    public function setDefaultOccupier(Request $request, $spotID, $userID)
    {
        $spot = Spot::find($spotID);
        $user = User::find($userID);
        if ($spot && $spot->iscurrentuseradmin && $user) {
            $spot->isoccupiedbydefault = true;
            $spot->occupiedbydefaultby = $user->id;
            $spot->occupiedby = $user->id;
            $spot->occupiedat = Carbon::now();
            $spot->save();

            return response()->json($spot);
        }

        return response('Bad Request', Response::HTTP_BAD_REQUEST);
    }

    public function changeStatus(SpotChangeStatusRequest $request, $id)
    {
        $spot = Spot::find($id);
        if ($spot && $spot->isparkinguser) {
            if (!$request->OccupiedAt) {
                if ($spot->occupier) {
                    $spot->releasedat = Carbon::now();
                }
                $spot->occupiedby = null;
                $spot->occupiedat = null;
            } else {
                $spot->occupiedby = Auth::user()->id;
                $spot->occupiedat = Carbon::now();
                $spot->occupiedat = null;
            }
            $spot->save();

            return response()->json($this->getSpotsOfAGivenParking($spot->parkingid));
        }

        return response('Bad Request'.$spot->id, Response::HTTP_BAD_REQUEST);
    }

    private function getSpotsOfAGivenParking(int $parkingID)
    {
        return Spot::with(['parking', 'occupier'])->where('parkingid', '=', $parkingID)->get();
    }
}
