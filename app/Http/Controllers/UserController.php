<?php

namespace App\Http\Controllers;

use App\Parking;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function me(Request $request)
    {
        if ($user = User::whereNotNull('authtoken')->where('authtoken', '=', $request->bearerToken())->first()) {
            return response()->json($user);
        }

        return response('Bad Request', Response::HTTP_BAD_REQUEST);
    }

    public function getInvitableUsers(Request $request, $parkingID, $search)
    {
        $parking = Parking::find($parkingID);
        if ($parking && $parking->iscurrentuseradmin && strlen($search) >= 3) {
            $userID = Auth::user()->id;
            return response()->json(User::all()->filter(function ($user) use ($search, $userID, $parkingID) {
                return stristr($user->username, $search)
                    && $user->id !== $userID
                    && count($user->parkings()->whereIn('parkings.id', [$parkingID])->get()) === 0;
            })->values());
        }

        return response('Bad Request', Response::HTTP_BAD_REQUEST);
    }
}
