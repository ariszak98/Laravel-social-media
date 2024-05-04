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
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');

// Blog related
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost'])->middleware('mustBeLoggedIn');
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post'); 
    // Edit + Update Post
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdatePost'])->middleware('can:update,post');

// Profile related
Route::get('/profile/{user:username}', [UserController::class, 'profile']);
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->middleware('mustBeLoggedIn');

// Admin related
Route::get('/admins-only', function() {
    return "Only admin can see this page.";
})->middleware('can:visitAdminPages');