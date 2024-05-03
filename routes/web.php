<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/**
 *  WEB ROUTES
 */
Route::get('/', [UserController::class, "showCorrectHomepage"]);

// Registration
Route::post('register', [UserController::class, "register"]);

// Login & Logout
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);