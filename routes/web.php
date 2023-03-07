<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

// Operations
Route::get('/', [UsuarioController::class, 'index']);
