<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/users', [UserController::class, 'store']); // Rota de registro público

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Rotas de usuário com Route Model Binding
    Route::apiResource('users', UserController::class)->except(['store']);

    // Rotas de endereço aninhadas com Route Model Binding
    Route::apiResource('users.addresses', AddressController::class)->parameters([
        'addresses' => 'address',
        'users' => 'user',
    ]);
});