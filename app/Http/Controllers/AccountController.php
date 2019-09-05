<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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

    public function updateUser(UserUpdateRequest $request)
    {
        $user = User::find($request->Id);
        if ($user && $user->id === Auth::user()->id) {
            if ($request->Email !== $user->email) {
                $existingEmail = User::where([
                    ['email', '=', $request->Email],
                    ['id', '!=', $user->id]
                ])
                    ->first();
                if ($existingEmail) {
                    return response('Email already exists.', Response::HTTP_BAD_REQUEST);
                }
            }
            $user->firstname = $request->Firstname;
            $user->lastname = $request->Lastname;
            $user->email = $request->Email;
            if ($request->Password && strlen($request->Password)) {
                $user->password = bcrypt($request->Password);
            }
            $user->save();

            return response()->json($user);
        }

        return response('Bad credentials', Response::HTTP_BAD_REQUEST);
    }
}
