<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function() {
    return 'Bem Vindo à Api TargetManager';
});

Route::namespace('App\Http\Controllers\Api')->group(function() {

    Route::group(['middleware' => 'api'], function() {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');

    });
    // Route::post('login', 'Auth\LoginJwtController@login');

    Route::group(['middleware' => 'jwt.auth'], function() {
        Route::resource('category', 'CategoryController');
        Route::resource('target', 'TargetController');
        Route::resource('stage', 'StageController');
    });

});
