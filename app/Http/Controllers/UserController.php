<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function me(Request $request)
    {
        if ($user = User::whereNotNull('authtoken')->where('authtoken', '=', $request->bearerToken())->first()) {
            return response()->json($user);
        }

        return response('Bad Request', Response::HTTP_BAD_REQUEST);
    }
}
