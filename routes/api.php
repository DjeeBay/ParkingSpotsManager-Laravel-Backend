<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['json-response', 'api'])->group(function () {
    Route::post('/account/CreateUser', 'AccountController@createUser');
    Route::post('/account/Login', 'AccountController@login')->name('login');
    Route::get('/users/me', 'UserController@me');

    Route::middleware('auth:api')->group(function () {
        Route::get('/parkings/GetUserParkings', 'ParkingController@getUserParkings');
        Route::get('/parkings/Leave/{parkingID}', 'ParkingController@leave');
        Route::post('/parkings/ChangeUserRole/{parkingID}', 'ParkingController@changeUserRole');
        Route::get('/parkings/{id}', 'ParkingController@get');

        Route::get('/spots/GetParkingSpots/{parkingID}', 'SpotController@getParkingSpots');
        Route::put('/spots/{id}/ChangeStatus', 'SpotController@changeStatus');
    });
});
