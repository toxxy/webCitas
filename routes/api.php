<?php

use App\Http\Controllers\Citas2Controller;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas públicas con rate limiting más restrictivo
Route::middleware(['rate.limit:20,1'])->group(function () {
    Route::get('/Citas', [Citas2Controller::class, 'index']);
    Route::get('/Citas/searcho/{order}', [Citas2Controller::class, 'searcho']);
    Route::get('/Citas/searchn/{name}', [Citas2Controller::class, 'searchn']);
});

// Rutas de autenticación con rate limiting muy restrictivo
Route::middleware(['rate.limit:5,1'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Rutas protegidas que requieren autenticación
Route::middleware(['auth:sanctum', 'rate.limit:100,1'])->group(function () {
    // CRUD de citas
    Route::post('/Citas', [Citas2Controller::class, 'store']);
    Route::put('/Citas/{id}', [Citas2Controller::class, 'update']);
    Route::get('/Citas/{id}', [Citas2Controller::class, 'show']);
    Route::delete('/Citas/{id}', [Citas2Controller::class, 'destroy']);
    
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/UInfo', [AuthController::class, 'UInfo']);
});

// Ruta de usuario (mantenida para compatibilidad)
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'data' => $request->user()
    ]);
});
