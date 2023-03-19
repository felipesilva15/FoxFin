<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'index']);

// Grupo de rotas que precisam que esteja autenticado
Route::group(['middleware'=> ['auth:sanctum']], function(){
    Route::get('/logout', [AuthController::class, 'logout']);
});
