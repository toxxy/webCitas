<?php

use App\Http\Controllers\Citas2Controller;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// No se necesita un token para mostrarlo
Route::get('/Citas/searcho/{order}', [Citas2Controller::class, 'searcho']);
Route::get('/Citas/searchn/{name}', [Citas2Controller::class, 'searchn']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/Citas', [Citas2Controller::class, 'index']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Se necesita un token para crear/modificar o eliminar un elemento
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/Citas', [Citas2Controller::class, 'store']);
    Route::put('/Citas/{id}', [Citas2Controller::class, 'update']);
    Route::get('/Citas/{id}', [Citas2Controller::class, 'show']);
    Route::delete('/Citas/{id}', [Citas2Controller::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/Citas/searcho/{order}', [Citas2Controller::class, 'searcho']);
    Route::get('/UInfo', [AuthController::class, 'UInfo']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
