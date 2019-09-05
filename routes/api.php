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
        Route::get('/users/GetInvitableUsers/{parkingID}/{search}', 'UserController@getInvitableUsers');

        Route::get('/parkings/GetUserParkings', 'ParkingController@getUserParkings');
        Route::get('/parkings/Leave/{parkingID}', 'ParkingController@leave');
        Route::post('/parkings/ChangeUserRole/{parkingID}', 'ParkingController@changeUserRole');
        Route::get('/parkings/{id}', 'ParkingController@get');
        Route::get('/parkings/RemoveUser/{parkingID}/{userID}', 'ParkingController@removeUser');
        Route::put('/parkings/{id}', 'ParkingController@update');
        Route::get('/parkings/GetUserList/{parkingID}/{search}', 'ParkingController@getUserList');
        Route::post('/parkings', 'ParkingController@store');
        Route::get('/parkings/SendInvitation/{parkingID}/{userID}', 'ParkingController@sendInvitation');

        Route::get('/spots/GetParkingSpots/{parkingID}', 'SpotController@getParkingSpots');
        Route::get('/spots/{id}', 'SpotController@get');
        Route::get('/spots/GetDefaultOccupier/{id}', 'SpotController@getDefaultOccupier');
        Route::get('/spots/SetDefaultOccupier/{spotID}/{userID}', 'SpotController@setDefaultOccupier');
        Route::put('/spots/{id}/ChangeStatus', 'SpotController@changeStatus');
        Route::put('/spots/{id}', 'SpotController@update');
        Route::delete('/spots/{id}', 'SpotController@delete');
        Route::post('/spots', 'SpotController@store');
    });
});
