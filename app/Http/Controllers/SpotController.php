<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpotChangeStatusRequest;
use App\Spot;
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
