<?php

use App\Http\Controllers\Citas2Controller;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// you didn't need a token to show it
Route::get('/Citas/searcho/{order}', [Citas2Controller::class, 'searcho']);
Route::get('/Citas/searchn/{name}', [Citas2Controller::class, 'searchn']);
Route::get('/Citas/{$id}', [Citas2Controller::class, 'show']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/Citas', [Citas2Controller::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);

//you need a token to create/modify or delete an element
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/Citas', [Citas2Controller::class, 'store']);
    Route::put('/Citas/{$id}', [Citas2Controller::class, 'update']);
    Route::get('/show/{$id}', [Citas2Controller::class, 'show']);
    Route::delete('/Citas/{$id}', [Citas2Controller::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::get('/Citas/searcho/{order}', [Citas2Controller::class, 'searcho']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::resource('Citas', Citas2Controller::class);

//     Route::get('/citas2', [PostController::class, 'Citas2']);
// Route::apiResource('/citas2', 'App\Http\Controllers\Api\PostController');
// Route::apiResource('/users', 'App\Http\Controllers\Api\PostController')->middleware('auth:sanctum');
// Route::delete('/citas2/{id}', 'App\Http\Controllers\Api\PostController@destroy');
//  Route::update('/citas2/{id}', 'App\Http\Controllers\Api\PostController@update');
