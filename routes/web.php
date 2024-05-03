<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\UserController;

/**
 *  WEB ROUTES
 */
Route::get('/', [ExampleController::class, "homepage"]);
Route::get('/about', [ExampleController::class, "aboutpage"]);

// Registration
Route::post('register', [UserController::class, "register"]);

// Login
Route::post('/login', [UserController::class, 'login']);