<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function createUser(CreateUserRequest $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json($user);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('username', '=', $request->login)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $user->authtoken = Str::random(60);
            $user->save();

            return response()->json($user);
        }

        return response('Bad credentials', Response::HTTP_BAD_REQUEST);
    }
}
