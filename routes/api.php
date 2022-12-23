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
    Route::post('user', 'UserController@store');

    Route::group(['middleware' => 'api'], function() {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');

    });
    // Route::post('login', 'Auth\LoginJwtController@login');

    Route::group(['middleware' => 'jwt.auth'], function() {
        Route::get('category/{id}/targets', 'CategoryController@targets');
        Route::get('category/categories', 'CategoryController@indexPivot');
        Route::get('category/father', 'CategoryController@indexFather');
        Route::resource('category', 'CategoryController');
        Route::resource('target', 'TargetController');
        Route::patch('stage/{id}', 'StageController@patch');
        Route::resource('stage', 'StageController');
    });

});
