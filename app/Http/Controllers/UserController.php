<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request)
    {
        if ($user = User::whereNotNull('authtoken')->where('authtoken', '=', $request->bearerToken())->first()) {
            return response()->json($user);
        }

        return response('Not Found', 404);
    }
}
