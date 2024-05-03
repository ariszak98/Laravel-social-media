<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/**
 *  WEB ROUTES
 */
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login');

// Registration
Route::post('register', [UserController::class, "register"])->middleware('guest');

// Login & Logout
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('guest');

// Blog related
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('auth');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('auth');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);