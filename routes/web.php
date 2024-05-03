<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
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

// Blog related
Route::get('/create-post', [PostController::class, 'showCreateForm']);
Route::post('/create-post', [PostController::class, 'storeNewPost']);